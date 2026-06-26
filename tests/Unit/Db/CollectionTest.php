<?php

namespace LaraCore\Tests\Unit\Db;

use LaraCore\Framework\Db\Collection;
use LaraCore\Tests\TestCase;

class CollectionTest extends TestCase
{
    private function makeCollection(array $items = []): Collection
    {
        return new Collection($items);
    }

    public function testAllReturnsAllItems(): void
    {
        $c = $this->makeCollection([1, 2, 3]);
        $this->assertSame([1, 2, 3], $c->all());
    }

    public function testFirstReturnsFirstItem(): void
    {
        $c = $this->makeCollection([10, 20, 30]);
        $this->assertSame(10, $c->first());
    }

    public function testFirstReturnsNullOnEmpty(): void
    {
        $this->assertNull($this->makeCollection()->first());
    }

    public function testLastReturnsLastItem(): void
    {
        $c = $this->makeCollection([10, 20, 30]);
        $this->assertSame(30, $c->last());
    }

    public function testCountReturnsNumberOfItems(): void
    {
        $this->assertSame(3, $this->makeCollection([1, 2, 3])->count());
    }

    public function testIsEmptyOnEmpty(): void
    {
        $this->assertTrue($this->makeCollection()->isEmpty());
    }

    public function testIsNotEmptyOnNonEmpty(): void
    {
        $this->assertTrue($this->makeCollection([1])->isNotEmpty());
    }

    public function testFilterRemovesNonMatchingItems(): void
    {
        $c = $this->makeCollection([1, 2, 3, 4, 5]);
        $even = $c->filter(fn($v) => $v % 2 === 0);
        $this->assertSame([2, 4], array_values($even->all()));
    }

    public function testMapTransformsItems(): void
    {
        $c = $this->makeCollection([1, 2, 3]);
        $doubled = $c->map(fn($v) => $v * 2);
        $this->assertSame([2, 4, 6], $doubled->all());
    }

    public function testPluckExtractsProperty(): void
    {
        $items = [
            (object)['name' => 'Alice', 'age' => 30],
            (object)['name' => 'Bob',   'age' => 25],
        ];
        $c = $this->makeCollection($items);
        $this->assertSame(['Alice', 'Bob'], $c->pluck('name')->all());
    }

    public function testContainsReturnsTrueWhenPresent(): void
    {
        $c = $this->makeCollection([1, 2, 3]);
        $this->assertTrue($c->contains(2));
        $this->assertFalse($c->contains(99));
    }

    public function testSortByAscending(): void
    {
        $items = [
            (object)['score' => 30],
            (object)['score' => 10],
            (object)['score' => 20],
        ];
        $c      = $this->makeCollection($items);
        $sorted = $c->sortBy('score')->all();
        $this->assertSame([10, 20, 30], array_column($sorted, 'score'));
    }

    public function testSortByDescDescending(): void
    {
        $items = [
            (object)['score' => 10],
            (object)['score' => 30],
            (object)['score' => 20],
        ];
        $c      = $this->makeCollection($items);
        $sorted = $c->sortByDesc('score')->all();
        $this->assertSame([30, 20, 10], array_column($sorted, 'score'));
    }

    public function testUnique(): void
    {
        $c = $this->makeCollection([1, 2, 2, 3, 3, 3]);
        $this->assertSame([1, 2, 3], array_values($c->unique()->all()));
    }

    public function testSlice(): void
    {
        $c = $this->makeCollection([1, 2, 3, 4, 5]);
        $this->assertSame([3, 4], array_values($c->slice(2, 2)->all()));
    }

    public function testTake(): void
    {
        $c = $this->makeCollection([1, 2, 3, 4, 5]);
        $this->assertSame([1, 2, 3], $c->take(3)->all());
    }

    public function testSkip(): void
    {
        $c = $this->makeCollection([1, 2, 3, 4, 5]);
        $this->assertSame([4, 5], array_values($c->skip(3)->all()));
    }

    public function testChunk(): void
    {
        $c      = $this->makeCollection([1, 2, 3, 4, 5]);
        $chunks = $c->chunk(2);
        $this->assertCount(3, $chunks);
        $this->assertSame([1, 2], $chunks[0]->all());
        $this->assertSame([3, 4], $chunks[1]->all());
        $this->assertSame([5],    $chunks[2]->all());
    }

    public function testMerge(): void
    {
        $c1 = $this->makeCollection([1, 2]);
        $c2 = $this->makeCollection([3, 4]);
        $this->assertSame([1, 2, 3, 4], $c1->merge($c2)->all());
    }

    public function testReverse(): void
    {
        $c = $this->makeCollection([1, 2, 3]);
        $this->assertSame([3, 2, 1], array_values($c->reverse()->all()));
    }

    public function testPush(): void
    {
        $c = $this->makeCollection([1, 2]);
        $c->push(3);
        $this->assertSame([1, 2, 3], $c->all());
    }

    public function testSum(): void
    {
        $c = $this->makeCollection([1, 2, 3, 4]);
        $this->assertSame(10.0, $c->sum());
    }

    public function testAvg(): void
    {
        $c = $this->makeCollection([2, 4, 6]);
        $this->assertSame(4.0, $c->avg());
    }

    public function testMax(): void
    {
        $c = $this->makeCollection([3, 1, 4, 1, 5]);
        $this->assertSame(5, $c->max());
    }

    public function testMin(): void
    {
        $c = $this->makeCollection([3, 1, 4, 1, 5]);
        $this->assertSame(1, $c->min());
    }

    public function testToArrayReturnsArray(): void
    {
        $c = $this->makeCollection([1, 2, 3]);
        $this->assertSame([1, 2, 3], $c->toArray());
    }

    public function testToJsonEncodesAsJson(): void
    {
        $c = $this->makeCollection([1, 2, 3]);
        $this->assertSame('[1,2,3]', $c->toJson());
    }

    public function testArrayAccessOffsetExists(): void
    {
        $c = $this->makeCollection(['a', 'b']);
        $this->assertTrue(isset($c[0]));
        $this->assertFalse(isset($c[5]));
    }

    public function testArrayAccessOffsetGet(): void
    {
        $c = $this->makeCollection(['x', 'y']);
        $this->assertSame('x', $c[0]);
    }

    public function testIteratorYieldsAllItems(): void
    {
        $c    = $this->makeCollection([10, 20, 30]);
        $vals = [];
        foreach ($c as $v) {
            $vals[] = $v;
        }
        $this->assertSame([10, 20, 30], $vals);
    }

    public function testEachCallsCallbackForEveryItem(): void
    {
        $c   = $this->makeCollection([1, 2, 3]);
        $sum = 0;
        $c->each(function ($v) use (&$sum) { $sum += $v; });
        $this->assertSame(6, $sum);
    }

    public function testFlatMap(): void
    {
        $c = $this->makeCollection([[1, 2], [3, 4]]);
        $result = $c->flatMap(fn($v) => $v);
        $this->assertSame([1, 2, 3, 4], $result->all());
    }
}
