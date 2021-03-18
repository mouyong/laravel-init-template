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
                'password' => $data['password'],
            ]);
    }
}