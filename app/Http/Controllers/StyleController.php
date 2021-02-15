<?php

namespace App\Http\Controllers;

use App\Http\Transformers\StyleTransformer;
use App\Services\StyleService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class StyleController extends Controller
{
    private $styleService;

    /**
     * @param StyleService $styleService
     * @param AuthManager $auth
     * @return void
     */
    public function __construct(StyleService $styleService, AuthManager $auth)
    {
        $this->styleService = $styleService;

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
            'name' => 'required|string|max:60|unique:styles',
            'background' => 'required|base64_image',
        ]);

        $this->styleService->create($data);

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
            'name' => 'string|max:60|unique:styles'.',id,'.$id,
            'background' => 'base64_image',
        ]);

        $style = $this->styleService->show($id);

        $this->styleService->update($style, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws BindingResolutionException
     */
    public function show(string $id): Response
    {
        $item = $this->styleService->show($id);

        return $this->itemResponse($item, new StyleTransformer, 200);
    }

    /**
     * @return Response
     * @throws BindingResolutionException
     */
    public function index(): Response
    {
        $list = $this->styleService->listStylePaginate();

        return $this->paginateResponse($list, new StyleTransformer, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->styleService->show($id);

        $this->styleService->destroy($item);

        return response(null, 200);
    }
}
