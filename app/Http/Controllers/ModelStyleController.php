<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ModelStyleTransformer;
use App\Services\ModelStyleService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ModelStyleController extends Controller
{
    private $modelStyleService;

    /**
     * @param ModelStyleService $modelStyleService
     * @param AuthManager $auth
     * @return void
     */
    public function __construct(ModelStyleService $modelStyleService, AuthManager $auth)
    {
        $this->modelStyleService = $modelStyleService;

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
            'name' => 'required|string|max:60|unique:model_styles',
            'background' => 'required|base64_image',
        ]);

        $this->modelStyleService->create($data);

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
            'name' => 'string|max:60|unique:model_styles'.',id,'.$id,
            'background' => 'base64_image',
        ]);

        $modelStyle = $this->modelStyleService->show($id);

        $this->modelStyleService->update($modelStyle, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws BindingResolutionException
     */
    public function show(string $id): Response
    {
        $item = $this->modelStyleService->show($id);

        return $this->itemResponse($item, new ModelStyleTransformer, 200);
    }

    /**
     * @return Response
     * @throws BindingResolutionException
     */
    public function index(): Response
    {
        $list = $this->modelStyleService->listModelStylePaginate();

        return $this->paginateResponse($list, new ModelStyleTransformer, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->modelStyleService->show($id);

        $this->modelStyleService->destroy($item);

        return response(null, 200);
    }
}
