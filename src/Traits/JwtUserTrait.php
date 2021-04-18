<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Trait JwtUserTrait
 * @mixin User
 * @package ZhenMu\LaravelInitTemplate\Traits
 */
trait JwtUserTrait
{
    public function setPasswordAttribute($value)
    {
        // 无密码
        if (!$value) {
            return;
        }

        // 迁移文件的默认密码长度为 60
        if (strlen($value) === 60) {
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function username($username = null)
    {
        $username = \request('username');

        $value = filter_var($username, FILTER_VALIDATE_INT);
        if ($value) return 'mobile';

        $value = filter_var($username, FILTER_VALIDATE_EMAIL);
        if ($value) return 'email';

        return $username ?? 'name';
    }
}