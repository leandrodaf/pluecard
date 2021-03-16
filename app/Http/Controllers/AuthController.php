<?php

namespace App\Http\Controllers;

use App\Http\Transformers\AuthenticationTransformer;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function socialLogin(Request $request, string $channel): Response
    {
        try {
            DB::beginTransaction();

            $token = $this->authService->social($channel, $request->all());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $this->itemResponse($token, new AuthenticationTransformer, 200);
    }

    public function login(Request $request): Response
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->authService->login($data['email'], $data['password']);

        return $this->itemResponse($token, new AuthenticationTransformer, 200);
    }

    public function refresh(): Response
    {
        $token = auth()->refresh();

        return $this->itemResponse($token, new AuthenticationTransformer, 200);
    }

    public function logout()
    {
        auth()->logout();
    }
}
