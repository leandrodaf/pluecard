<?php

namespace App\Http\Controllers;

use App\Http\Transformers\TransactionTransformer;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    private $userService;

    private $auth;

    private $transactionService;

    /**
     * @param UserService $userService
     * @param AuthManager $auth
     * @param TransactionService $transactionService
     * @return void
     */
    public function __construct(UserService $userService, AuthManager $auth, TransactionService $transactionService)
    {
        $this->userService = $userService;

        $this->auth = $auth;

        $this->transactionService = $transactionService;
    }

    /**
     * @return Response
     * @throws BindingResolutionException
     */
    public function index(): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $list = $this->transactionService->listTransactionsPaginate($this->auth->user());

        return $this->paginateResponse($list, new TransactionTransformer, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws BindingResolutionException
     */
    public function show(string $id): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $item = $this->transactionService->show($this->auth->user(), $id);

        return $this->itemResponse($item, new TransactionTransformer, 200);
    }

    /**
     * @param string $userId
     * @return Response
     * @throws BindingResolutionException
     */
    public function listByUser(string $userId): Response
    {
        Gate::authorize('admin', $this->auth->user());

        $user = $this->userService->show($userId);

        $list = $this->transactionService->listTransactionsPaginate($user);

        return $this->paginateResponse($list, new TransactionTransformer, 200);
    }

    /**
     * @param string $userId
     * @param string $id
     * @return Response
     * @throws BindingResolutionException
     */
    public function showByUser(string $userId, string $id): Response
    {
        Gate::authorize('admin', $this->auth->user());

        $user = $this->userService->show($userId);

        $item = $this->transactionService->show($user, $id);

        return $this->itemResponse($item, new TransactionTransformer, 200);
    }
}
