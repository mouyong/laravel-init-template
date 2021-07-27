<?php

namespace ZhenMu\LaravelInitTemplate\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::query()
            ->create([
                'name' => $data['username'],
                'password' => $data['password'] ?? 'zhenmu-123456',
            ]);
    }

    public function createByOAuth(array $data)
    {
        return User::query()
            ->updateOrCreate([
                'mobile' => $data['mobile'],
            ], [
                'parent_id' => $data['parent_id'] ?? null,
                'name' => $data['name'],
                'realname' => $data['realname'] ?? null,
                'avatar' => $data['avatar'] ?? null,
                'id_card' => $data['id_card'] ?? null,
                'ip' => $data['ip'] ?? null,
            ]);
    }

    public function findByMobile(string $mobile)
    {
        return User::query()->where('mobile', $mobile)->first();
    }
}