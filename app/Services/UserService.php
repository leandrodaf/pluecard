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

    /** @return Paginator  */
    public function listUsersPaginate(): Paginator
    {
        return $this->user->simplePaginate(15);
    }

    /**
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
     * @param string $id
     * @return User
     */
    public function show(string $id): User
    {
        return $this->user->where('id', $id)->firstOrFail();
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
