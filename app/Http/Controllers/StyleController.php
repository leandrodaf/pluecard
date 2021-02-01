<?php

namespace App\Http\Controllers;

use App\Services\ModelStyleService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StyleController extends Controller
{
    private $modelStyleService;

    public function __construct(ModelStyleService $modelStyleService, AuthManager $auth)
    {
        $this->modelStyleService = $modelStyleService;

        Gate::authorize('admin', $auth->user());
    }

    public function create(Request $request): Response
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:60',
            'background' => 'required|base64_image:jpg, jpeg, png',
        ]);

        $this->modelStyleService->create($data);

        return response(null, 200);
    }

    public function update(): Response
    {
        return response(null, 200);
    }

    public function show(): Response
    {
        return response(null, 200);
    }

    public function index(): Response
    {
        return response(null, 200);
    }

    public function destroy(): Response
    {
        return response(null, 200);
    }
}
