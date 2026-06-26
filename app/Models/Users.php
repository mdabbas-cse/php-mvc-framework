<?php

namespace LaraCore\App\Models;

use LaraCore\Framework\Db\Model;
use LaraCore\Framework\Db\QueryBuilder;
use LaraCore\Framework\Helpers\Hash;
use LaraCore\Framework\Session;

/**
 * Users model.
 *
 * New-style ORM usage:
 *   Users::all()
 *   Users::find(1)
 *   Users::findOrFail(1)
 *   Users::where('email', 'a@b.com')->first()
 *   Users::where('status', 1)->orderBy('firstname')->get()
 *   Users::creating(['firstname' => 'Alice', 'email' => 'a@b.com', 'password' => 'secret'])
 *   Users::firstOrCreate(['email' => 'a@b.com'], ['firstname' => 'Guest'])
 *   Users::count()
 *   Users::active()->get()
 */
class Users extends Model
{
    // -------------------------------------------------------------------------
    // Schema
    // -------------------------------------------------------------------------

    protected static $table      = 'users';
    protected static $primaryKey = 'id';
    protected static $timestamps = true;

    protected $fillable = ['firstname', 'lastname', 'email', 'password', 'status'];

    protected $hidden = ['password'];

    protected $casts = ['status' => 'int'];

    // -------------------------------------------------------------------------
    // Lifecycle hooks
    // -------------------------------------------------------------------------

    protected function onCreating(): void
    {
        if (!empty($this->attributes['password'])) {
            $this->attributes['password'] = Hash::make($this->attributes['password']);
        }
        $this->attributes['status'] = 1;
    }

    // -------------------------------------------------------------------------
    // Local scope:  Users::active()->get()
    // -------------------------------------------------------------------------

    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('status', 1);
    }

    // -------------------------------------------------------------------------
    // Business logic
    // -------------------------------------------------------------------------

    /**
     * Attempt to authenticate using the current email/password attributes.
     * Stores the authenticated user in the session on success.
     */
    public function login(): bool
    {
        /** @var Users|null $user */
        $user = static::where('email', $this->attributes['email'] ?? '')->first();
        if ($user === null) {
            return false;
        }
        $inputPassword = $this->attributes['password'] ?? '';
        if (Hash::verify($inputPassword, $user->getAttribute('password'))) {
            Session::set('user', $user);
            return true;
        }
        return false;
    }

    // -------------------------------------------------------------------------
    // Relations — uncomment as your schema grows
    // -------------------------------------------------------------------------

    // public function posts(): \LaraCore\Framework\Db\Relations\HasMany
    // {
    //     return $this->hasMany(\LaraCore\App\Models\Post::class);
    // }
}
