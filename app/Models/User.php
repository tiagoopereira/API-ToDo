<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, UsesUuid, HasApiTokens;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = ['password'];

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
