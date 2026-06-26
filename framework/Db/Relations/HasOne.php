<?php

namespace LaraCore\Framework\Db\Relations;

use LaraCore\Framework\Db\Model;

/**
 * One-to-one: parent has exactly one related record.
 *
 * Example:
 *   class User extends Model {
 *       public function profile(): HasOne {
 *           return $this->hasOne(Profile::class);
 *           // profile.user_id = users.id
 *       }
 *   }
 *   $user->profile->bio;
 */
class HasOne extends Relation
{
    /**
     * Fetch the single related model or null.
     *
     * @return Model|null
     */
    public function getResults(): ?Model
    {
        return $this->related::where(
            $this->foreignKey,
            $this->parent->{$this->localKey}
        )->first();
    }
}
