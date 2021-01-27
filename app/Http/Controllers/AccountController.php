<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AccountService;
use App\Http\Controllers\AuthController;

class AccountController extends Controller
{
    private $accountService;

    private $authController;

    public function __construct(AccountService $accountService, AuthController $authController)
    {
        $this->accountService = $accountService;

        $this->authController = $authController;
    }

    public function register(Request $request): Response
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:155|min:3',
            'acceptTerms' => 'accepted',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
            'newsletter' => 'required|boolean',
            'discountCoupons' => 'required|boolean',
        ]);

        $this->accountService->create($data);

        return response(null, 201);
    }

    public function confirmation(Request $request, string $token)
    {

        dd(
            $token
        );
        
        $request->merge([
            'email' => '5508-foo@gmail.com',
            'password' => '2-foo-bar'
        ]);

        return $this->authController->login($request);
    }
}
