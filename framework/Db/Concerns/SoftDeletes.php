<?php

namespace LaraCore\Framework\Db\Concerns;

use LaraCore\Framework\Db\Collection;
use LaraCore\Framework\Db\QueryBuilder;

/**
 * SoftDeletes trait — marks records as deleted instead of removing them.
 *
 * Usage:
 *   class Post extends Model {
 *       use SoftDeletes;
 *   }
 *
 *   $post->delete();           // sets deleted_at, does NOT remove the row
 *   $post->restore();          // clears deleted_at
 *   $post->forceDelete();      // permanently removes the row
 *   $post->trashed();          // true when deleted_at is set
 *
 *   Post::withTrashed()->get()    // includes soft-deleted
 *   Post::onlyTrashed()->get()    // only soft-deleted
 *
 * Pattern: Mixin (trait) — augments the model without inheritance.
 */
trait SoftDeletes
{
    protected static string $deletedAtColumn = 'deleted_at';

    // -------------------------------------------------------------------------
    // Override Model::delete() to soft-delete
    // -------------------------------------------------------------------------

    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        $col = static::$deletedAtColumn;
        $this->setAttribute($col, date('Y-m-d H:i:s'));
        return $this->save();
    }

    /**
     * Permanently remove the row from the database.
     */
    public function forceDelete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        $pk = static::getPrimaryKeyName();
        static::newQuery()
            ->where($pk, $this->getAttribute($pk))
            ->deleteRecords();
        $this->exists = false;
        return true;
    }

    /**
     * Restore a soft-deleted model.
     */
    public function restore(): bool
    {
        $this->setAttribute(static::$deletedAtColumn, null);
        return $this->save();
    }

    /**
     * Whether this model has been soft-deleted.
     */
    public function trashed(): bool
    {
        return $this->getAttribute(static::$deletedAtColumn) !== null;
    }

    // -------------------------------------------------------------------------
    // Static query scopes
    // -------------------------------------------------------------------------

    /**
     * Include soft-deleted records in query results.
     */
    public static function withTrashed(): QueryBuilder
    {
        return static::newQuery();
    }

    /**
     * Return only soft-deleted records.
     */
    public static function onlyTrashed(): QueryBuilder
    {
        return static::newQuery()->whereNotNull(static::$deletedAtColumn);
    }

    /**
     * Default query scope: exclude soft-deleted records.
     * Called automatically by the QueryBuilder scope system.
     */
    public function scopeWithoutTrashed(QueryBuilder $query): QueryBuilder
    {
        return $query->whereNull(static::$deletedAtColumn);
    }
}
