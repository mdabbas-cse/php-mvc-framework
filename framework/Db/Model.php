<?php

namespace LaraCore\Framework\Db;

use LaraCore\Framework\Db\Relations\BelongsTo;
use LaraCore\Framework\Db\Relations\BelongsToMany;
use LaraCore\Framework\Db\Relations\HasMany;
use LaraCore\Framework\Db\Relations\HasOne;

/**
 * Active Record ORM base — Eloquent-style.
 *
 * Design patterns applied:
 *   - Active Record   : each model instance represents and persists one row
 *   - Template Method : onCreating(), onSaving() hooks for subclass lifecycle callbacks
 *   - Proxy / Magic   : __callStatic forwards to QueryBuilder; __get/__set access $attributes
 *   - Identity Map    : $relations cache prevents repeated relation queries
 *   - Factory Method  : newFromBuilder() creates hydrated instances from DB rows
 *
 * Quick reference:
 *   User::all()
 *   User::find(1)
 *   User::where('email', 'a@b.com')->first()
 *   User::where('age', '>', 18)->orderBy('name')->limit(10)->get()
 *   User::creating(['name' => 'Alice'])
 *   User::firstOrCreate(['email' => 'a@b.com'], ['name' => 'Alice'])
 *   $user->posts()   // HasMany relation
 *   $user->role()    // BelongsTo relation
 */
abstract class Model
{
    // -------------------------------------------------------------------------
    // Schema configuration — override in child classes
    // -------------------------------------------------------------------------

    /** @var string  Override to set a custom table name. */
    protected static $table = '';

    /** @var string  Primary key column name. */
    protected static $primaryKey = 'id';

    /** @var bool  Auto-manage created_at / updated_at. */
    protected static $timestamps = true;

    /** @var string[] Columns that may be mass-assigned. Empty means use $guarded. */
    protected $fillable = [];

    /** @var string[] Columns protected from mass-assignment. ['*'] = all. */
    protected $guarded = ['*'];

    /** @var string[] Columns hidden from toArray() / toJson(). */
    protected $hidden = [];

    /** @var string[] Columns cast to native PHP types. e.g. ['is_active' => 'bool'] */
    protected $casts = [];

    // -------------------------------------------------------------------------
    // Runtime state
    // -------------------------------------------------------------------------

    protected array $attributes = [];
    protected array $original   = [];
    protected array $relations  = [];

    /** Whether the model exists in the database. */
    public bool $exists = false;

    // -------------------------------------------------------------------------
    // Lifecycle hooks — override in child models
    // Called automatically at the right moment during persistence operations.
    // -------------------------------------------------------------------------

    protected function onCreating(): void {}
    protected function onCreated(): void {}
    protected function onUpdating(): void {}
    protected function onUpdated(): void {}
    protected function onSaving(): void {}
    protected function onSaved(): void {}
    protected function onDeleting(): void {}
    protected function onDeleted(): void {}

    // -------------------------------------------------------------------------
    // Table & key resolution
    // -------------------------------------------------------------------------

    /**
     * Resolve the table name: static::$table or auto snake_case + plural.
     * e.g. UserProfile -> user_profiles
     */
    public static function getTable(): string
    {
        if (static::$table !== '') {
            return static::$table;
        }
        $short = (new \ReflectionClass(static::class))->getShortName();
        $snake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $short));
        // simple plural: append 's' unless it already ends in 's'
        return rtrim($snake, 's') . 's';
    }

    public static function getPrimaryKeyName(): string
    {
        return static::$primaryKey;
    }

    // -------------------------------------------------------------------------
    // Query factory
    // -------------------------------------------------------------------------

    protected static function newQuery(): QueryBuilder
    {
        return new QueryBuilder(static::class);
    }

    /**
     * Forward any unrecognized static calls to a fresh QueryBuilder.
     * Enables: User::where(...), User::orderBy(...), User::latest(), etc.
     */
    public static function __callStatic(string $name, array $args)
    {
        return static::newQuery()->$name(...$args);
    }

    /**
     * Forward any unrecognized instance calls to a fresh QueryBuilder.
     * Enables scope chaining on instances.
     */
    public function __call(string $name, array $args)
    {
        return static::newQuery()->$name(...$args);
    }

    // -------------------------------------------------------------------------
    // Static convenience API  (mirrors Eloquent)
    // -------------------------------------------------------------------------

    public static function all(): Collection
    {
        return static::newQuery()->get();
    }

    public static function find($id)
    {
        return static::newQuery()->find($id);
    }

    public static function findOrFail($id)
    {
        return static::newQuery()->findOrFail($id);
    }

    public static function where($column, $operatorOrValue = null, $value = null): QueryBuilder
    {
        return static::newQuery()->where($column, $operatorOrValue, $value);
    }

    public static function whereIn(string $column, array $values): QueryBuilder
    {
        return static::newQuery()->whereIn($column, $values);
    }

    public static function whereNull(string $column): QueryBuilder
    {
        return static::newQuery()->whereNull($column);
    }

    public static function whereNotNull(string $column): QueryBuilder
    {
        return static::newQuery()->whereNotNull($column);
    }

    public static function orderBy(string $column, string $direction = 'ASC'): QueryBuilder
    {
        return static::newQuery()->orderBy($column, $direction);
    }

    public static function latest(string $column = 'created_at'): QueryBuilder
    {
        return static::newQuery()->latest($column);
    }

    public static function oldest(string $column = 'created_at'): QueryBuilder
    {
        return static::newQuery()->oldest($column);
    }

    public static function limit(int $n): QueryBuilder
    {
        return static::newQuery()->limit($n);
    }

    public static function count(): int
    {
        return static::newQuery()->count();
    }

    public static function exists(): bool
    {
        return static::newQuery()->exists();
    }

    /**
     * Create and persist a new model instance.
     *
     * @param array $attributes
     * @return static
     */
    public static function creating(array $attributes): self
    {
        $model = new static();
        $model->fill($attributes);
        $model->saving();
        return $model;
    }

    /**
     * Find first matching record or create it.
     *
     * @param array $search   Columns used for lookup
     * @param array $extra    Additional columns set only on create
     * @return static
     */
    public static function firstOrCreate(array $search, array $extra = []): self
    {
        $query = static::newQuery();
        foreach ($search as $col => $val) {
            $query->where($col, $val);
        }
        $found = $query->first();
        if ($found !== null) {
            return $found;
        }
        return static::creating(array_merge($search, $extra));
    }

    /**
     * Find first matching record and update it, or create it.
     *
     * @param array $search   Columns used for lookup
     * @param array $extra    Columns to set (update or create)
     * @return static
     */
    public static function updateOrCreate(array $search, array $extra = []): self
    {
        $query = static::newQuery();
        foreach ($search as $col => $val) {
            $query->where($col, $val);
        }
        $found = $query->first();
        if ($found !== null) {
            $found->updated($extra);
            return $found;
        }
        return static::creating(array_merge($search, $extra));
    }

    // -------------------------------------------------------------------------
    // Attribute handling
    // -------------------------------------------------------------------------

    /**
     * Mass-assign attributes respecting $fillable / $guarded.
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    /**
     * Mass-assign without fillable/guarded checks (internal use).
     */
    public function forceFill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function setAttribute(string $key, $value): void
    {
        // Check for mutator: setXxxAttribute
        $mutator = 'set' . $this->studly($key) . 'Attribute';
        if (method_exists($this, $mutator)) {
            $this->$mutator($value);
            return;
        }
        $this->attributes[$key] = $this->castValue($key, $value);
    }

    public function getAttribute(string $key)
    {
        // Check for accessor: getXxxAttribute
        $accessor = 'get' . $this->studly($key) . 'Attribute';
        if (method_exists($this, $accessor)) {
            return $this->$accessor($this->attributes[$key] ?? null);
        }

        // Resolve loaded or lazy relation
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }
        if (method_exists($this, $key) && !method_exists(self::class, $key)) {
            $relation = $this->$key();
            if ($relation instanceof \LaraCore\Framework\Db\Relations\Relation) {
                $this->relations[$key] = $relation->getResults();
                return $this->relations[$key];
            }
        }

        return $this->castValue($key, $this->attributes[$key] ?? null);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setRawAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    public function isDirty(string $key = null): bool
    {
        if ($key !== null) {
            return ($this->attributes[$key] ?? null) !== ($this->original[$key] ?? null);
        }
        return $this->attributes !== $this->original;
    }

    public function getOriginal(string $key = null)
    {
        return $key ? ($this->original[$key] ?? null) : $this->original;
    }

    public function getDirty(): array
    {
        $dirty = [];
        foreach ($this->attributes as $key => $value) {
            if (!array_key_exists($key, $this->original) || $value !== $this->original[$key]) {
                $dirty[$key] = $value;
            }
        }
        return $dirty;
    }

    // -------------------------------------------------------------------------
    // Persistence
    // -------------------------------------------------------------------------

    public function saving(): bool
    {
        $this->onSaving();

        if ($this->exists) {
            $result = $this->performUpdate();
        } else {
            $result = $this->performInsert();
        }

        if ($result) {
            $this->onSaved();
            $this->syncOriginal();
        }
        return $result;
    }

    /**
     * Update specific columns and persist immediately.
     *
     * @param array $attributes
     * @return bool
     */
    public function updated(array $attributes = []): bool
    {
        if (!empty($attributes)) {
            $this->fill($attributes);
        }
        return $this->saving();
    }

    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        $this->onDeleting();
        $pk  = static::getPrimaryKeyName();
        $deleted = static::newQuery()
            ->where($pk, $this->attributes[$pk])
            ->deleteRecords() > 0;
        if ($deleted) {
            $this->exists = false;
            $this->onDeleted();
        }
        return $deleted;
    }

    /**
     * Re-fetch this model from the DB and return a fresh instance.
     */
    public function fresh(): self
    {
        $pk = static::getPrimaryKeyName();
        return static::find($this->attributes[$pk]);
    }

    /**
     * Re-fetch and update this instance in-place.
     */
    public function refresh(): self
    {
        $pk      = static::getPrimaryKeyName();
        $fresh   = static::find($this->attributes[$pk]);
        $this->attributes = $fresh->attributes;
        $this->syncOriginal();
        $this->relations = [];
        return $this;
    }

    protected function performInsert(): bool
    {
        $this->onCreating();

        $attrs = $this->attributes;
        if (static::$timestamps) {
            $now = date('Y-m-d H:i:s');
            $attrs['created_at'] = $now;
            $attrs['updated_at'] = $now;
        }

        $qb = static::newQuery();
        $qb->insertRecord($attrs);

        $this->attributes[static::getPrimaryKeyName()] = $qb->getLastInsertId();
        if (static::$timestamps) {
            $this->attributes['created_at'] = $attrs['created_at'];
            $this->attributes['updated_at'] = $attrs['updated_at'];
        }
        $this->exists = true;
        $this->onCreated();
        return true;
    }

    protected function performUpdate(): bool
    {
        $dirty = $this->getDirty();
        if (empty($dirty)) {
            return true;
        }

        $this->onUpdating();

        if (static::$timestamps) {
            $dirty['updated_at']              = date('Y-m-d H:i:s');
            $this->attributes['updated_at']   = $dirty['updated_at'];
        }

        $pk = static::getPrimaryKeyName();
        static::newQuery()
            ->where($pk, $this->attributes[$pk])
            ->updateRecords($dirty);

        $this->onUpdated();
        return true;
    }

    // -------------------------------------------------------------------------
    // Hydration (Factory Method)
    // -------------------------------------------------------------------------

    /**
     * Hydrate a new instance from a raw DB row — marks it as existing.
     *
     * @param array $row
     * @return static
     */
    public function newFromBuilder(array $row): self
    {
        $model = new static();
        $model->setRawAttributes($row);
        $model->syncOriginal();
        $model->exists = true;
        return $model;
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * One-to-one: this model has one $related.
     * e.g. User -> hasOne(Profile::class)
     */
    public function hasOne(string $related, string $foreignKey = null, string $localKey = null): HasOne
    {
        $fk = $foreignKey ?? $this->guessForeignKey();
        $lk = $localKey   ?? static::getPrimaryKeyName();
        return new HasOne($this, $related, $fk, $lk);
    }

    /**
     * One-to-many: this model has many $related.
     * e.g. User -> hasMany(Post::class)
     */
    public function hasMany(string $related, string $foreignKey = null, string $localKey = null): HasMany
    {
        $fk = $foreignKey ?? $this->guessForeignKey();
        $lk = $localKey   ?? static::getPrimaryKeyName();
        return new HasMany($this, $related, $fk, $lk);
    }

    /**
     * Inverse: this model belongs to $related.
     * e.g. Post -> belongsTo(User::class)
     */
    public function belongsTo(string $related, string $foreignKey = null, string $ownerKey = null): BelongsTo
    {
        $fk = $foreignKey ?? $this->guessBelongsToFk($related);
        $ok = $ownerKey   ?? $related::getPrimaryKeyName();
        return new BelongsTo($this, $related, $fk, $ok);
    }

    /**
     * Many-to-many via pivot table.
     * e.g. User -> belongsToMany(Role::class)
     */
    public function belongsToMany(
        string $related,
        string $pivotTable = null,
        string $foreignKey = null,
        string $relatedKey = null
    ): BelongsToMany {
        $pivot = $pivotTable ?? $this->guessPivotTable($related);
        $fk    = $foreignKey ?? $this->guessForeignKey();
        $rk    = $relatedKey ?? $this->guessRelatedKey($related);
        return new BelongsToMany($this, $related, $pivot, $fk, $rk);
    }

    // -------------------------------------------------------------------------
    // Serialization
    // -------------------------------------------------------------------------

    public function toArray(): array
    {
        $attrs = $this->attributes;
        foreach ($this->hidden as $key) {
            unset($attrs[$key]);
        }
        foreach ($this->relations as $key => $value) {
            $attrs[$key] = $value instanceof Collection
                ? $value->toArray()
                : (method_exists($value, 'toArray') ? $value->toArray() : (array) $value);
        }
        return $attrs;
    }

    public function toJson(int $flags = 0): string
    {
        return json_encode($this->toArray(), $flags);
    }

    // -------------------------------------------------------------------------
    // Magic attribute access
    // -------------------------------------------------------------------------

    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    public function __set(string $name, $value): void
    {
        $this->setAttribute($name, $value);
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]) || isset($this->relations[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->attributes[$name], $this->relations[$name]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function isFillable(string $key): bool
    {
        if (!empty($this->fillable)) {
            return in_array($key, $this->fillable, true);
        }
        if ($this->guarded === ['*']) {
            return false;
        }
        return !in_array($key, $this->guarded, true);
    }

    protected function castValue(string $key, $value)
    {
        if (!isset($this->casts[$key])) {
            return $value;
        }
        switch ($this->casts[$key]) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'float':
            case 'double':
                return (float) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'array':
                return is_string($value) ? json_decode($value, true) : (array) $value;
            case 'json':
                return is_string($value) ? json_decode($value, true) : $value;
            case 'string':
                return (string) $value;
            default:
                return $value;
        }
    }

    private function studly(string $key): string
    {
        return str_replace('_', '', ucwords($key, '_'));
    }

    private function guessForeignKey(): string
    {
        $short = (new \ReflectionClass($this))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $short)) . '_id';
    }

    private function guessBelongsToFk(string $related): string
    {
        $short = (new \ReflectionClass($related))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $short)) . '_id';
    }

    private function guessPivotTable(string $related): string
    {
        $tables = [static::getTable(), (new $related())->getTable()];
        sort($tables);
        return implode('_', $tables);
    }

    private function guessRelatedKey(string $related): string
    {
        $short = (new \ReflectionClass($related))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $short)) . '_id';
    }

    // -------------------------------------------------------------------------
    // Legacy helpers kept for DataModel compatibility
    // -------------------------------------------------------------------------

    /** @deprecated Use static::newQuery() or fluent API */
    public function prepare(string $sql): \PDOStatement
    {
        return Connection::getInstance()->prepare($sql);
    }
}
