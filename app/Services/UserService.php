<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class UserService extends Services
{
    private $user;

    /**
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * List all user with options for search using email or name.
     * @param string|null $search
     * @return Paginator
     */
    public function listUsersPaginate(string $search = null): Paginator
    {
        return $this->user->search($search)->simplePaginate(15);
    }

    /**
     * Update user.
     *
     * @param User $user
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(User $user, array $data): bool
    {
        return $user->fillAndSave($data);
    }

    /**
     * Show Single User by id.
     *
     * @param string $id
     * @return User
     */
    public function show(string $id): User
    {
        return $this->user->where('id', $id)->firstOrFail();
    }

    /**
     * Delete user.
     *
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
