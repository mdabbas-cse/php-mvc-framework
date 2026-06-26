<?php

namespace LaraCore\Framework\Db;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

/**
 * Eloquent-like collection wrapping an array of model instances.
 *
 * Patterns used:
 *   - Iterator   : foreach support
 *   - ArrayAccess: bracket-offset access  $col[0]
 *   - Decorator  : wraps a plain PHP array and adds higher-order helpers
 */
class Collection implements ArrayAccess, Countable, Iterator, JsonSerializable
{
    protected array $items;
    private int $position = 0;

    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    // -------------------------------------------------------------------------
    // Retrieval
    // -------------------------------------------------------------------------

    public function all(): array
    {
        return $this->items;
    }

    public function first(callable $callback = null)
    {
        if ($callback === null) {
            return $this->items[0] ?? null;
        }
        foreach ($this->items as $item) {
            if ($callback($item)) {
                return $item;
            }
        }
        return null;
    }

    public function last(callable $callback = null)
    {
        if ($callback === null) {
            $count = count($this->items);
            return $count > 0 ? $this->items[$count - 1] : null;
        }
        return $this->filter($callback)->last();
    }

    public function nth(int $index)
    {
        return $this->items[$index] ?? null;
    }

    // -------------------------------------------------------------------------
    // Transformation
    // -------------------------------------------------------------------------

    public function filter(callable $callback): self
    {
        return new self(array_values(array_filter($this->items, $callback)));
    }

    public function map(callable $callback): self
    {
        return new self(array_map($callback, $this->items));
    }

    public function flatMap(callable $callback): self
    {
        $result = [];
        foreach ($this->items as $item) {
            $mapped = $callback($item);
            if (is_array($mapped)) {
                foreach ($mapped as $v) {
                    $result[] = $v;
                }
            } else {
                $result[] = $mapped;
            }
        }
        return new self($result);
    }

    public function each(callable $callback): self
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    public function pluck(string $key): self
    {
        return $this->map(function ($item) use ($key) {
            return is_array($item) ? ($item[$key] ?? null) : ($item->$key ?? null);
        });
    }

    public function sortBy(string $key, string $direction = 'asc'): self
    {
        $items = $this->items;
        usort($items, function ($a, $b) use ($key, $direction) {
            $valA = is_array($a) ? ($a[$key] ?? null) : ($a->$key ?? null);
            $valB = is_array($b) ? ($b[$key] ?? null) : ($b->$key ?? null);
            return $direction === 'asc' ? $valA <=> $valB : $valB <=> $valA;
        });
        return new self($items);
    }

    public function sortByDesc(string $key): self
    {
        return $this->sortBy($key, 'desc');
    }

    public function unique(string $key = null): self
    {
        if ($key === null) {
            return new self(array_values(array_unique($this->items)));
        }
        $seen  = [];
        $items = [];
        foreach ($this->items as $item) {
            $val = is_array($item) ? ($item[$key] ?? null) : ($item->$key ?? null);
            if (!in_array($val, $seen, true)) {
                $seen[]  = $val;
                $items[] = $item;
            }
        }
        return new self($items);
    }

    public function merge(self $other): self
    {
        return new self(array_merge($this->items, $other->all()));
    }

    public function reverse(): self
    {
        return new self(array_reverse($this->items));
    }

    public function slice(int $offset, int $length = null): self
    {
        return new self(array_slice($this->items, $offset, $length));
    }

    public function take(int $n): self
    {
        return $this->slice(0, $n);
    }

    public function skip(int $n): self
    {
        return $this->slice($n);
    }

    public function chunk(int $size): self
    {
        $chunks = array_chunk($this->items, $size);
        return new self(array_map(fn($c) => new self($c), $chunks));
    }

    public function push($item): self
    {
        $this->items[] = $item;
        return $this;
    }

    // -------------------------------------------------------------------------
    // Aggregates
    // -------------------------------------------------------------------------

    public function sum(string $key = null): float
    {
        if ($key === null) {
            return (float) array_sum($this->items);
        }
        return (float) array_sum($this->pluck($key)->all());
    }

    public function avg(string $key = null): ?float
    {
        $count = $this->count();
        return $count > 0 ? (float) ($this->sum($key) / $count) : null;
    }

    public function max(string $key = null)
    {
        $values = $key ? $this->pluck($key)->all() : $this->items;
        return !empty($values) ? max($values) : null;
    }

    public function min(string $key = null)
    {
        $values = $key ? $this->pluck($key)->all() : $this->items;
        return !empty($values) ? min($values) : null;
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function contains($keyOrCallback, $value = null): bool
    {
        if (is_callable($keyOrCallback)) {
            return $this->first($keyOrCallback) !== null;
        }
        if ($value !== null) {
            return $this->first(function ($item) use ($keyOrCallback, $value) {
                $v = is_array($item) ? ($item[$keyOrCallback] ?? null) : ($item->$keyOrCallback ?? null);
                return $v === $value;
            }) !== null;
        }
        return in_array($keyOrCallback, $this->items, true);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    // -------------------------------------------------------------------------
    // Serialisation
    // -------------------------------------------------------------------------

    public function toArray(): array
    {
        return array_map(function ($item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                return $item->toArray();
            }
            if (is_object($item)) {
                return (array) $item;
            }
            return $item;
        }, $this->items);
    }

    public function toJson(int $flags = 0): string
    {
        return json_encode($this->toArray(), $flags);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    // -------------------------------------------------------------------------
    // Countable
    // -------------------------------------------------------------------------

    public function count(): int
    {
        return count($this->items);
    }

    // -------------------------------------------------------------------------
    // ArrayAccess
    // -------------------------------------------------------------------------

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
        $this->items = array_values($this->items);
    }

    // -------------------------------------------------------------------------
    // Iterator
    // -------------------------------------------------------------------------

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
