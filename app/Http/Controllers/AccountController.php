<?php

namespace App\Http\Controllers;

use App\Http\Transformers\AuthenticationTransformer;
use App\Services\AccountService;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AuthService $authService
    ) {
    }

    public function register(Request $request): Response
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:155|min:3',
            'accept_terms' => 'accepted',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
            'newsletter' => 'required|boolean',
            'discount_coupons' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $this->accountService->create($data);

            DB::commit();
        } catch (Exception $exec) {
            DB::rollBack();

            throw $exec;
        }

        return response(null, 201);
    }

    public function confirmationEmail(Request $request): Response
    {
        $payload = $this->validate($request, [
            'hash' => 'required|string',
        ]);

        $user = $this->authService->hashConfirmation($payload['hash']);

        $token = auth()->login($user);

        return response()->item($token, new AuthenticationTransformer, 200);
    }

    public function refreshConfirmationEmail(Request $request): Response
    {
        $payload = $this->validate($request, [
            'email' => 'required|email',
        ]);

        $this->authService->refreshHash($payload['email']);

        return response(null, 200);
    }

    public function resetPassword(Request $request): Response
    {
        $this->accountService->resetPassword($request->user());

        return response(null, 200);
    }

    public function updatePassword(Request $request): Response
    {
        $data = $this->validate($request, [
            'hash' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        $this->accountService->resetPasswordUpdate($request->user(), $data);

        return response(null, 200);
    }

    public function forgotPassword(Request $request): Response
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
        ]);

        $this->accountService->forgotPassword($data['email']);

        return response(null, 200);
    }

    public function forgotPasswordConfirmation(Request $request): Response
    {
        $data = $this->validate($request, [
            'hash' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        $this->accountService->forgotPasswordConfirmation($data['hash'], $data['password']);

        return response(null, 200);
    }
}
