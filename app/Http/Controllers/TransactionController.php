<?php

namespace App\Http\Controllers;

use App\Http\Transformers\TransactionTransformer;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthManager $auth,
        private TransactionService $transactionService
    ) {
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(): JsonResponse
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $list = $this->transactionService->listTransactionsPaginate($this->auth->user());

        return response()->collection($list, new TransactionTransformer, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $id): JsonResponse
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $item = $this->transactionService->show($this->auth->user(), $id);

        return response()->item($item, new TransactionTransformer, 200);
    }

    /**
     * @param string $userId
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function listByUser(string $userId): JsonResponse
    {
        Gate::authorize('admin', $this->auth->user());

        $user = $this->userService->show($userId);

        $list = $this->transactionService->listTransactionsPaginate($user);

        return response()->collection($list, new TransactionTransformer, 200);
    }

    /**
     * @param string $userId
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function showByUser(string $userId, string $id): JsonResponse
    {
        Gate::authorize('admin', $this->auth->user());

        $user = $this->userService->show($userId);

        $item = $this->transactionService->show($user, $id);

        return response()->item($item, new TransactionTransformer, 200);
    }
}
