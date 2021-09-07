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
        $user = User::query()
            ->create([
                'parent_id' => $data['parent_id'] ?? null,
                'name' => $data['name'],
                'avatar' => $data['avatar'] ?? null,
            ]);

        $user->profile()->updateOrCreate([
            'mobile' => $data['mobile'],
        ], [
            'realname' => $data['realname'] ?? null,
            'id_card' => $data['id_card'] ?? null,

        ]);
        dump($user);
        return $user;
    }

    public function findByMobile(string $mobile)
    {
        return User::query()->join('profiles', 'profiles.user_id', '=', 'users.id')->where('mobile', $mobile)->first();
    }
}