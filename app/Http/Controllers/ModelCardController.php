<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ModelCardTransformer;
use App\Services\ModelCardService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ModelCardController extends Controller
{
    public function __construct(
        private ModelCardService $modelCardService,
        private AuthManager $auth
    ) {
    }

    public function create(Request $request): Response
    {
        Gate::authorize('admin', $this->auth->user());

        $data = $this->validate($request, [
            'name' => 'required|string|max:60',
            'background' => 'required|base64_image',
            'body' => 'required|string',
            'styles' => 'required|array',
            'styles.*' => 'required|integer|exists:styles_cards,id',
            'colors' => 'required|array',
            'colors.*.id' => 'required|integer|exists:colors_cards,id',
            'colors.*.status' => 'required|in:PRIMARY,SECONDARY,CUSTOM',
        ]);

        try {
            DB::beginTransaction();

            $this->modelCardService->create($data);

            DB::commit();
        } catch (Exception $exec) {
            DB::rollBack();

            throw $exec;
        }

        return response(null, 201);
    }

    public function update(Request $request, string $id): Response
    {
        Gate::authorize('admin', $this->auth->user());

        $data = $this->validate($request, [
            'name' => 'string|max:60',
            'background' => 'base64_image',
            'body' => 'string',
            'styles' => 'array',
            'styles.*' => 'integer|exists:styles_cards,id',
            'colors' => 'array',
            'colors.*.id' => 'integer|exists:colors_cards,id',
            'colors.*.status' => 'in:PRIMARY,SECONDARY,CUSTOM',
        ]);

        $modelCard = $this->modelCardService->show($id);

        try {
            DB::beginTransaction();

            $this->modelCardService->update($modelCard, $data);

            DB::commit();
        } catch (Exception $exec) {
            DB::rollBack();

            throw $exec;
        }

        return response(null, 200);
    }

    public function show(string $id): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $item = $this->modelCardService->show($id);

        return $this->itemResponse($item, new ModelCardTransformer, 200);
    }

    public function index(Request $request): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->auth->user());

        $search = $request->input('search') ?? null;
        $styles = $request->input('styles') ?? [];

        $list = $this->modelCardService->listModelCardPaginate($search, $styles);

        return $this->paginateResponse($list, new ModelCardTransformer, 200);
    }

    public function destroy(string $id): Response
    {
        Gate::authorize('admin', $this->auth->user());

        $item = $this->modelCardService->show($id);

        $this->modelCardService->destroy($item);

        return response(null, 200);
    }
}
