<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Http\Transformers\UserTransformer;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private $userService;

    private $auth;

    public function __construct(UserService $userService, AuthManager $auth)
    {
        $this->userService = $userService;

        $this->auth = $auth;
    }

    public function index(Request $request): Response
    {
        Gate::authorize('admin', $this->auth->user());

        $search = $request->input('search') ?? null;

        $list = $this->userService->listUsersPaginate($search);

        return $this->paginateResponse($list, new UserTransformer, 200);
    }

    public function meUpdate(Request $request): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $data = $this->validate($request, [
            'name' => 'string',
            'newsletter' => 'boolean',
            'discount_coupons' => 'boolean',
        ]);

        $this->userService->update($this->auth->user(), $data);

        return response(null, 200);
    }

    public function update(Request $request, string $id): Response
    {
        $user = $this->userService->show($id);

        Gate::authorize('adminOrSimpleUser', $user);

        $data = $this->validate($request, [
            'name' => 'string',
            'newsletter' => 'boolean',
            'discount_coupons' => 'boolean',
        ]);

        $this->userService->update($user, $data);

        return response(null, 200);
    }

    public function me(): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        return $this->itemResponse($this->auth->user(), new UserTransformer, 200);
    }

    public function show(string $id): Response
    {
        $user = $this->userService->show($id);

        Gate::authorize('adminOrSimpleUser', $user);

        return $this->itemResponse($user, new UserTransformer, 200);
    }

    public function meDestroy(): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $this->userService->delete($this->auth->user());

        return response(null, 200);
    }

    public function destroy(string $id): Response
    {
        $user = $this->userService->show($id);

        Gate::authorize('adminOrSimpleUser', $user);

        throw_if($user->isAdmin(), ValidatorException::class, ['user' => 'It is not possible to delete an administrator user']);

        $this->userService->delete($user);

        return response(null, 200);
    }
}
