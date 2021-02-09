<?php

namespace App\Http\Controllers;

use App\Http\Transformers\AuthenticationTransformer;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function socialLogin(Request $request, string $channel): Response
    {
        $token = $this->authService->social($channel, $request->all());

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
