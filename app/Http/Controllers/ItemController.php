<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ItemsTransformer;
use App\Services\ItemService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $itemService,
        AuthManager $auth
    ) {
        Gate::authorize('admin', $auth->user());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): Response
    {
        $data = $this->validate($request, [
            'title' => 'required|string|max:155',
            'description' => 'required|string|max:255',
            'picture_url' => 'base64_image',
            'category_id' => 'required|string|in:cards',
            'unit_price' => 'required|numeric',
        ]);

        $this->itemService->create($data);

        return response(null, 201);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ValidationException
     * @throws MassAssignmentException
     */
    public function update(Request $request, string $id): Response
    {
        $data = $this->validate($request, [
            'title' => 'string|max:155',
            'description' => 'string|max:255',
            'picture_url' => 'base64_image',
            'category_id' => 'string|in:cards',
            'unit_price' => 'numeric',
        ]);

        $style = $this->itemService->show($id);

        $this->itemService->update($style, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $id): JsonResponse
    {
        $item = $this->itemService->show($id);

        return response()->item($item, new ItemsTransformer, 200);
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(): JsonResponse
    {
        $list = $this->itemService->listItemsPaginate();

        return response()->collection($list, new ItemsTransformer, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->itemService->show($id);

        $this->itemService->destroy($item);

        return response(null, 200);
    }
}
