<?php

/**
 * DataModel — backward-compatible shim over the new Model base.
 *
 * Existing models that extend DataModel continue to work unchanged.
 * New models should extend Model directly for the full Eloquent-like API.
 *
 * Migration guide:
 *
 *   Before (old style):
 *     class Posts extends DataModel {
 *         public function tableName(): string { return 'posts'; }
 *         public function attributes(): array { return ['title', 'body']; }
 *     }
 *
 *   After (new style — extend Model directly):
 *     class Post extends Model {
 *         protected static $table   = 'posts';
 *         protected $fillable       = ['title', 'body'];
 *     }
 */

namespace LaraCore\Framework\Db;

abstract class DataModel extends Model
{
    // -------------------------------------------------------------------------
    // Legacy abstract contract (still supported by existing models)
    // -------------------------------------------------------------------------

    /**
     * Return the table name for this model.
     * In new models use  protected static $table = '...';  instead.
     */
    abstract public function tableName(): string;

    /**
     * Return the list of fillable column names.
     * In new models use  protected $fillable = [...];  instead.
     */
    abstract public function attributes(): array;

    // -------------------------------------------------------------------------
    // Constructor: seed new-style fillable from the legacy attributes() call
    // -------------------------------------------------------------------------

    public function __construct()
    {
        $this->fillable = $this->attributes();
    }

    // -------------------------------------------------------------------------
    // Bridge: legacy tableName() -> new Model::getTable()
    // -------------------------------------------------------------------------

    public static function getTable(): string
    {
        // DataModel children define tableName() as an instance method,
        // so we need a temporary instance to resolve it.
        $instance = new static();
        return $instance->tableName();
    }

    // -------------------------------------------------------------------------
    // Legacy helpers — wrappers around the new fluent API
    // -------------------------------------------------------------------------

    /**
     * Return all rows.  Replacement: static::all()
     */
    public function getAll(): Collection
    {
        return static::all();
    }

    /**
     * SELECT specific columns.  Replacement: static::newQuery()->select(...)->get()
     *
     * @param string[] $columns
     */
    public function selectColumns(array $columns = ['*']): Collection
    {
        return static::newQuery()->select(...$columns)->get();
    }

    /**
     * SELECT specific columns WHERE conditions match.
     * Replacement: static::where(...)->get()
     *
     * @param string[] $columns
     * @param array<string,mixed> $where
     */
    public function selectWhere(array $columns = ['*'], array $where = []): Collection
    {
        $qb = static::newQuery()->select(...$columns);
        foreach ($where as $col => $val) {
            $qb->where($col, $val);
        }
        return $qb->get();
    }

    /**
     * Find rows matching all given conditions.
     * Replacement: static::where(...)->get()
     *
     * @param array<string,mixed> $conditions
     */
    public function findOne(array $conditions = []): Collection
    {
        $qb = static::newQuery();
        foreach ($conditions as $col => $val) {
            $qb->where($col, $val);
        }
        return $qb->get();
    }
}
