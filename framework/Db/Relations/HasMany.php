<?php

namespace LaraCore\Framework\Db\Relations;

use LaraCore\Framework\Db\Collection;

/**
 * One-to-many: parent has many related records.
 *
 * Example:
 *   class User extends Model {
 *       public function posts(): HasMany {
 *           return $this->hasMany(Post::class);
 *           // post.user_id = users.id
 *       }
 *   }
 *   $user->posts->each(fn($p) => ...);
 */
class HasMany extends Relation
{
    /**
     * Fetch all related models as a Collection.
     *
     * @return Collection
     */
    public function getResults(): Collection
    {
        return $this->related::where(
            $this->foreignKey,
            $this->parent->{$this->localKey}
        )->get();
    }
}
