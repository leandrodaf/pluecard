<?php

namespace App\Http\Controllers;

use App\Http\Transformers\AuthenticationTransformer;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function socialLogin(Request $request, string $channel): JsonResponse
    {
        try {
            DB::beginTransaction();

            $token = $this->authService->social($channel, $request->all());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return response()->item($token, new AuthenticationTransformer, 200);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->authService->login($data['email'], $data['password']);

        return response()->item($token, new AuthenticationTransformer, 200);
    }

    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();

        return response()->item($token, new AuthenticationTransformer, 200);
    }

    public function logout()
    {
        auth()->logout();
    }
}
