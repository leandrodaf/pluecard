<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\ConfirmationEmailRequest;
use App\Http\Requests\Account\ForgotPasswordConfirmationRequest;
use App\Http\Requests\Account\ForgotPasswordRequest;
use App\Http\Requests\Account\RefreshConfirmationEmailRequest;
use App\Http\Requests\Account\RegisterRequest;
use App\Http\Requests\Account\UpdatePasswordRequest;
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

    public function register(RegisterRequest $registerRequest, Request $request): Response
    {
        $data = $registerRequest->validator($request);

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

    public function confirmationEmail(ConfirmationEmailRequest $confirmationEmailRequest, Request $request): Response
    {
        $payload = $confirmationEmailRequest->validator($request);

        $user = $this->authService->hashConfirmation($payload['hash']);

        $token = auth()->login($user);

        return response()->item($token, new AuthenticationTransformer, 200);
    }

    public function refreshConfirmationEmail(RefreshConfirmationEmailRequest $refreshConfirmationEmailRequest, Request $request): Response
    {
        $payload = $refreshConfirmationEmailRequest->validator($request);

        $this->authService->refreshHash($payload['email']);

        return response(null, 200);
    }

    public function resetPassword(Request $request): Response
    {
        $this->accountService->resetPassword($request->user());

        return response(null, 200);
    }

    public function updatePassword(UpdatePasswordRequest $updatePasswordRequest, Request $request): Response
    {
        $data = $$updatePasswordRequest->validator($request);

        $this->accountService->resetPasswordUpdate($request->user(), $data);

        return response(null, 200);
    }

    public function forgotPassword(ForgotPasswordRequest $forgotPasswordRequest, Request $request): Response
    {
        $data = $forgotPasswordRequest->validator($request);

        $this->accountService->forgotPassword($data['email']);

        return response(null, 200);
    }

    public function forgotPasswordConfirmation(ForgotPasswordConfirmationRequest $forgotPasswordConfirmationRequest, Request $request): Response
    {
        $data = $forgotPasswordConfirmationRequest->validator($request);

        $this->accountService->forgotPasswordConfirmation($data['hash'], $data['password']);

        return response(null, 200);
    }
}
