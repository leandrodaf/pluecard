<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ItemsTransformer;
use App\Services\ItemService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    private $itemService;

    /**
     * @param ItemService $itemService
     * @param AuthManager $auth
     * @return void
     */
    public function __construct(ItemService $itemService, AuthManager $auth)
    {
        $this->itemService = $itemService;

        Gate::authorize('admin', $auth->user());
    }

    /**
     * @param Request $request
     * @return Response
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
     * @return Response
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

        $modelStyle = $this->itemService->show($id);

        $this->itemService->update($modelStyle, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws BindingResolutionException
     */
    public function show(string $id): Response
    {
        $item = $this->itemService->show($id);

        return $this->itemResponse($item, new ItemsTransformer, 200);
    }

    /**
     * @return Response
     * @throws BindingResolutionException
     */
    public function index(): Response
    {
        $list = $this->itemService->listItemsPaginate();

        return $this->paginateResponse($list, new ItemsTransformer, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->itemService->show($id);

        $this->itemService->destroy($item);

        return response(null, 200);
    }
}
