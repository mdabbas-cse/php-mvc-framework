<?php

namespace LaraCore\Framework\Db\Relations;

use LaraCore\Framework\Db\Model;

/**
 * Inverse one-to-one / many: child belongs to a parent.
 *
 * Example:
 *   class Post extends Model {
 *       public function author(): BelongsTo {
 *           return $this->belongsTo(User::class);
 *           // post.user_id -> users.id
 *       }
 *   }
 *   $post->author->name;
 */
class BelongsTo extends Relation
{
    /** The PK column on the owner (parent) table. */
    protected string $ownerKey;

    public function __construct(Model $parent, string $related, string $foreignKey, string $ownerKey)
    {
        $this->parent     = $parent;
        $this->related    = $related;
        $this->foreignKey = $foreignKey;
        $this->ownerKey   = $ownerKey;
    }

    /**
     * Fetch the owning model or null.
     *
     * @return Model|null
     */
    public function getResults(): ?Model
    {
        return $this->related::where(
            $this->ownerKey,
            $this->parent->{$this->foreignKey}
        )->first();
    }
}
