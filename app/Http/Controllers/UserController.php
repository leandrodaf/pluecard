<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Http\Transformers\UserTransformer;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthManager $auth
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('admin', $this->auth->user());

        $search = $request->input('search') ?? null;

        $list = $this->userService->listUsersPaginate($search);

        return response()->collection($list, new UserTransformer, 200);
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

    public function me(): JsonResponse
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        return response()->item($this->auth->user(), new UserTransformer, 200);
    }

    public function show(string $id): JsonResponse
    {
        $user = $this->userService->show($id);

        Gate::authorize('adminOrSimpleUser', $user);

        return response()->item($user, new UserTransformer, 200);
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
