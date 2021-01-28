<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\Paginator;

class UserService extends Services
{
    public function listUsersPaginate(): Paginator
    {
        return User::simplePaginate(15);
    }

    public function update(User $user, array $data): bool
    {
        return $user->fillAndSave($data);
    }

    public function show(string $id): User
    {
        return User::where('id', $id)->firstOrFail();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
