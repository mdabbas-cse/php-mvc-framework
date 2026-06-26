<?php

namespace LaraCore\Framework\Db;

use LaraCore\Framework\Db\Interfaces\QueryBuilderInterface;
use PDO;
use PDOStatement;
use RuntimeException;

/**
 * Fluent SQL query builder — Eloquent-style chaining.
 *
 * Design patterns:
 *   - Builder   : every mutating method returns $this for chaining
 *   - Strategy  : binding logic is encapsulated per clause type
 *   - Template  : toSql() assembles clauses in a fixed order
 *
 * Usage:
 *   User::where('status', 1)->orderBy('name')->limit(10)->get();
 *   User::where('age', '>', 18)->orWhere('vip', 1)->first();
 */
class QueryBuilder implements QueryBuilderInterface
{
    protected string $modelClass;
    protected \PDO $pdo;
    protected string $table;

    protected array $selects   = ['*'];
    protected bool  $distinct  = false;
    protected array $wheres    = [];  // [type, col, op, val, boolean]
    protected array $orders    = [];  // [col, dir]
    protected array $groups    = [];
    protected array $havings   = [];
    protected array $joins     = [];  // [type, table, first, op, second]
    protected ?int  $limitVal  = null;
    protected ?int  $offsetVal = null;
    protected array $bindings  = [];
    protected int   $bindIdx   = 0;

    // -------------------------------------------------------------------------
    // Bootstrap
    // -------------------------------------------------------------------------

    public function __construct(string $modelClass, \PDO $pdo = null)
    {
        $this->modelClass = $modelClass;
        $this->table      = $modelClass::getTable();
        $this->pdo        = $pdo ?? Connection::getInstance();
    }

    // -------------------------------------------------------------------------
    // SELECT
    // -------------------------------------------------------------------------

    public function select(...$columns): self
    {
        $this->selects = array_merge(...array_map(function ($col) {
            return is_array($col) ? $col : [$col];
        }, $columns));
        return $this;
    }

    public function addSelect(string ...$columns): self
    {
        foreach ($columns as $col) {
            $this->selects[] = $col;
        }
        return $this;
    }

    public function distinct(): self
    {
        $this->distinct = true;
        return $this;
    }

    // -------------------------------------------------------------------------
    // WHERE clauses
    // -------------------------------------------------------------------------

    /**
     * Add a basic WHERE clause.
     * Two-arg form:   where('col', 'val')        -> col = val
     * Three-arg form: where('col', '>', 'val')   -> col > val
     * Closure form:   where(callable)            -> grouped sub-condition
     */
    public function where($column, $operatorOrValue = null, $value = null, string $boolean = 'AND'): self
    {
        if (is_callable($column)) {
            return $this->whereNested($column, $boolean);
        }

        if ($value === null && $operatorOrValue !== null) {
            $this->wheres[] = ['type' => 'basic', 'col' => $column, 'op' => '=', 'val' => $operatorOrValue, 'boolean' => $boolean];
        } else {
            $this->wheres[] = ['type' => 'basic', 'col' => $column, 'op' => $operatorOrValue, 'val' => $value, 'boolean' => $boolean];
        }
        return $this;
    }

    public function orWhere($column, $operatorOrValue = null, $value = null): self
    {
        return $this->where($column, $operatorOrValue, $value, 'OR');
    }

    public function whereNested(callable $callback, string $boolean = 'AND'): self
    {
        $nested = new self($this->modelClass);
        $callback($nested);
        if (!empty($nested->wheres)) {
            $this->wheres[] = ['type' => 'nested', 'query' => $nested, 'boolean' => $boolean];
        }
        return $this;
    }

    public function whereIn(string $column, array $values, string $boolean = 'AND', bool $not = false): self
    {
        $this->wheres[] = ['type' => 'in', 'col' => $column, 'values' => $values, 'boolean' => $boolean, 'not' => $not];
        return $this;
    }

    public function whereNotIn(string $column, array $values): self
    {
        return $this->whereIn($column, $values, 'AND', true);
    }

    public function whereNull(string $column, string $boolean = 'AND', bool $not = false): self
    {
        $this->wheres[] = ['type' => 'null', 'col' => $column, 'boolean' => $boolean, 'not' => $not];
        return $this;
    }

    public function whereNotNull(string $column): self
    {
        return $this->whereNull($column, 'AND', true);
    }

    public function whereBetween(string $column, array $range, string $boolean = 'AND', bool $not = false): self
    {
        $this->wheres[] = ['type' => 'between', 'col' => $column, 'range' => $range, 'boolean' => $boolean, 'not' => $not];
        return $this;
    }

    public function whereNotBetween(string $column, array $range): self
    {
        return $this->whereBetween($column, $range, 'AND', true);
    }

    public function whereLike(string $column, string $value, string $boolean = 'AND'): self
    {
        $this->wheres[] = ['type' => 'basic', 'col' => $column, 'op' => 'LIKE', 'val' => '%' . $value . '%', 'boolean' => $boolean];
        return $this;
    }

    public function whereRaw(string $sql, array $bindings = [], string $boolean = 'AND'): self
    {
        $this->wheres[] = ['type' => 'raw', 'sql' => $sql, 'bindings' => $bindings, 'boolean' => $boolean];
        return $this;
    }

    // -------------------------------------------------------------------------
    // JOINs
    // -------------------------------------------------------------------------

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = compact('type', 'table', 'first', 'operator', 'second');
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    // -------------------------------------------------------------------------
    // ORDER / GROUP / LIMIT
    // -------------------------------------------------------------------------

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orders[] = [$column, strtoupper($direction)];
        return $this;
    }

    public function latest(string $column = 'created_at'): self
    {
        return $this->orderBy($column, 'DESC');
    }

    public function oldest(string $column = 'created_at'): self
    {
        return $this->orderBy($column, 'ASC');
    }

    public function groupBy(string ...$columns): self
    {
        foreach ($columns as $col) {
            $this->groups[] = $col;
        }
        return $this;
    }

    public function having(string $column, string $operator, $value): self
    {
        $this->havings[] = compact('column', 'operator', 'value');
        return $this;
    }

    public function limit(int $value): self
    {
        $this->limitVal = $value;
        return $this;
    }

    public function take(int $value): self
    {
        return $this->limit($value);
    }

    public function offset(int $value): self
    {
        $this->offsetVal = $value;
        return $this;
    }

    public function skip(int $value): self
    {
        return $this->offset($value);
    }

    public function forPage(int $page, int $perPage = 15): self
    {
        return $this->offset(($page - 1) * $perPage)->limit($perPage);
    }

    // -------------------------------------------------------------------------
    // SQL assembly
    // -------------------------------------------------------------------------

    public function toSql(): string
    {
        $this->bindings = [];
        $this->bindIdx  = 0;

        $distinct = $this->distinct ? 'DISTINCT ' : '';
        $cols     = implode(', ', $this->selects);
        $sql      = "SELECT {$distinct}{$cols} FROM `{$this->table}`";

        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN `{$join['table']}` ON {$join['first']} {$join['operator']} {$join['second']}";
        }

        $whereClause = $this->compileWheres($this->wheres);
        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }

        if (!empty($this->groups)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }

        if (!empty($this->havings)) {
            $havParts = array_map(function ($h) {
                $key = $this->nextKey();
                $this->bindings[$key] = $h['value'];
                return "{$h['column']} {$h['operator']} :{$key}";
            }, $this->havings);
            $sql .= ' HAVING ' . implode(' AND ', $havParts);
        }

        if (!empty($this->orders)) {
            $orderParts = array_map(fn($o) => "{$o[0]} {$o[1]}", $this->orders);
            $sql .= ' ORDER BY ' . implode(', ', $orderParts);
        }

        if ($this->limitVal !== null) {
            $sql .= ' LIMIT ' . $this->limitVal;
        }
        if ($this->offsetVal !== null) {
            $sql .= ' OFFSET ' . $this->offsetVal;
        }

        return $sql;
    }

    private function compileWheres(array $wheres): string
    {
        $parts = [];
        foreach ($wheres as $i => $where) {
            $clause = $this->compileWhere($where);
            $prefix = ($i === 0) ? '' : $where['boolean'] . ' ';
            $parts[] = $prefix . $clause;
        }
        return implode(' ', $parts);
    }

    private function compileWhere(array $where): string
    {
        switch ($where['type']) {
            case 'basic':
                $key = $this->nextKey();
                $this->bindings[$key] = $where['val'];
                return "`{$where['col']}` {$where['op']} :{$key}";

            case 'nested':
                $inner = $this->compileWheres($where['query']->wheres);
                $this->bindings = array_merge($this->bindings, $where['query']->bindings);
                return "({$inner})";

            case 'in':
                if (empty($where['values'])) {
                    return $where['not'] ? '1=1' : '1=0';
                }
                $keys = [];
                foreach ($where['values'] as $val) {
                    $k = $this->nextKey();
                    $this->bindings[$k] = $val;
                    $keys[] = ':' . $k;
                }
                $not = $where['not'] ? 'NOT ' : '';
                return "`{$where['col']}` {$not}IN (" . implode(', ', $keys) . ')';

            case 'null':
                $not = $where['not'] ? 'NOT ' : '';
                return "`{$where['col']}` IS {$not}NULL";

            case 'between':
                [$from, $to] = $where['range'];
                $k1 = $this->nextKey();
                $k2 = $this->nextKey();
                $this->bindings[$k1] = $from;
                $this->bindings[$k2] = $to;
                $not = $where['not'] ? 'NOT ' : '';
                return "`{$where['col']}` {$not}BETWEEN :{$k1} AND :{$k2}";

            case 'raw':
                foreach ($where['bindings'] as $k => $v) {
                    $this->bindings[$k] = $v;
                }
                return $where['sql'];

            default:
                return '1=1';
        }
    }

    private function nextKey(): string
    {
        return 'b' . ($this->bindIdx++);
    }

    // -------------------------------------------------------------------------
    // Execution — SELECT
    // -------------------------------------------------------------------------

    public function get(): Collection
    {
        $stmt = $this->executeSelect();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $model = $this->modelClass;
        $instances = array_map(function ($row) use ($model) {
            return (new $model())->newFromBuilder($row);
        }, $rows);
        return new Collection($instances);
    }

    public function first()
    {
        return $this->limit(1)->get()->first();
    }

    public function firstOrFail()
    {
        $result = $this->first();
        if ($result === null) {
            throw new RuntimeException("No record found for model [{$this->modelClass}].");
        }
        return $result;
    }

    public function find($id)
    {
        $pk = $this->modelClass::getPrimaryKeyName();
        return $this->where($pk, $id)->first();
    }

    public function findOrFail($id)
    {
        $pk = $this->modelClass::getPrimaryKeyName();
        return $this->where($pk, $id)->firstOrFail();
    }

    // -------------------------------------------------------------------------
    // Aggregates
    // -------------------------------------------------------------------------

    public function count(string $column = '*'): int
    {
        return (int) $this->aggregate('COUNT', $column);
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    public function doesntExist(): bool
    {
        return !$this->exists();
    }

    public function sum(string $column)
    {
        return $this->aggregate('SUM', $column);
    }

    public function max(string $column)
    {
        return $this->aggregate('MAX', $column);
    }

    public function min(string $column)
    {
        return $this->aggregate('MIN', $column);
    }

    public function avg(string $column)
    {
        return $this->aggregate('AVG', $column);
    }

    private function aggregate(string $fn, string $column)
    {
        $col = $column === '*' ? '*' : "`{$column}`";
        $this->selects = ["{$fn}({$col}) as aggregate"];
        $stmt = $this->executeSelect();
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['aggregate'] ?? null;
    }

    // -------------------------------------------------------------------------
    // Pagination
    // -------------------------------------------------------------------------

    public function paginate(int $perPage = 15, int $page = 1): array
    {
        $total = (clone $this)->count();
        $items = $this->forPage($page, $perPage)->get();
        return [
            'data'         => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    // -------------------------------------------------------------------------
    // Mutation queries
    // -------------------------------------------------------------------------

    public function insertRecord(array $fields): bool
    {
        $this->bindings = [];
        $this->bindIdx  = 0;
        $keys   = array_keys($fields);
        $params = [];
        foreach ($keys as $col) {
            $k = $this->nextKey();
            $params[$col] = ':' . $k;
            $this->bindings[$k] = $fields[$col];
        }
        $colList   = implode(', ', array_map(fn($c) => "`{$c}`", $keys));
        $paramList = implode(', ', $params);
        $sql  = "INSERT INTO `{$this->table}` ({$colList}) VALUES ({$paramList})";
        $stmt = $this->pdo->prepare($sql);
        $this->bindAll($stmt);
        return $stmt->execute();
    }

    public function updateRecords(array $fields): int
    {
        $this->bindings = [];
        $this->bindIdx  = 0;
        $setParts = [];
        foreach ($fields as $col => $val) {
            $k = $this->nextKey();
            $this->bindings[$k] = $val;
            $setParts[] = "`{$col}` = :{$k}";
        }
        $sql  = "UPDATE `{$this->table}` SET " . implode(', ', $setParts);
        $whereClause = $this->compileWheres($this->wheres);
        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }
        $stmt = $this->pdo->prepare($sql);
        $this->bindAll($stmt);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteRecords(): int
    {
        $this->bindings = [];
        $this->bindIdx  = 0;
        $sql = "DELETE FROM `{$this->table}`";
        $whereClause = $this->compileWheres($this->wheres);
        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }
        $stmt = $this->pdo->prepare($sql);
        $this->bindAll($stmt);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    // -------------------------------------------------------------------------
    // Legacy QueryBuilderInterface compliance
    // -------------------------------------------------------------------------

    public function selectAll($table, $intoClass = null)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$table}`");
        $stmt->execute();
        if ($intoClass) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, $intoClass);
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function rawSelect($table, $columns = ['*'], $where = [], $intoClass = null)
    {
        $cols  = implode(', ', $columns);
        $query = "SELECT {$cols} FROM `{$table}`";
        if (!empty($where)) {
            $conditions = implode(' AND ', array_map(fn($k) => "`{$k}` = :{$k}", array_keys($where)));
            $query .= ' WHERE ' . $conditions;
        }
        $stmt = $this->pdo->prepare($query);
        foreach ($where as $col => $val) {
            $stmt->bindValue(':' . $col, $val);
        }
        $stmt->execute();
        if ($intoClass) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, $intoClass);
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // -------------------------------------------------------------------------
    // Scope forwarding
    // -------------------------------------------------------------------------

    public function __call(string $name, array $args): self
    {
        $scope = 'scope' . ucfirst($name);
        $model = new $this->modelClass();
        if (method_exists($model, $scope)) {
            $result = $model->$scope($this, ...$args);
            return $result ?? $this;
        }
        throw new \BadMethodCallException("Method [{$name}] does not exist on " . get_class($this) . '.');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function executeSelect(): PDOStatement
    {
        $sql  = $this->toSql();
        $stmt = $this->pdo->prepare($sql);
        $this->bindAll($stmt);
        $stmt->execute();
        return $stmt;
    }

    private function bindAll(PDOStatement $stmt): void
    {
        foreach ($this->bindings as $key => $value) {
            $stmt->bindValue(':' . $key, $value, $this->pdoType($value));
        }
    }

    private function pdoType($value): int
    {
        if (is_int($value))   return PDO::PARAM_INT;
        if (is_bool($value))  return PDO::PARAM_BOOL;
        if (is_null($value))  return PDO::PARAM_NULL;
        return PDO::PARAM_STR;
    }
}
