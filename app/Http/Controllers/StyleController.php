<?php

namespace App\Http\Controllers;

use App\Http\Transformers\StyleTransformer;
use App\Services\StyleService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class StyleController extends Controller
{
    public function __construct(
        private StyleService $styleService,
        AuthManager $auth
    ) {
        $this->styleService = $styleService;

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
            'name' => 'required|string|max:60|unique:styles_cards',
            'background' => 'required|base64_image',
        ]);

        $this->styleService->create($data);

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
            'name' => 'string|max:60|unique:styles_cards'.',id,'.$id,
            'background' => 'base64_image',
        ]);

        $style = $this->styleService->show($id);

        $this->styleService->update($style, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $id): JsonResponse
    {
        $item = $this->styleService->show($id);

        return response()->item($item, new StyleTransformer, 200);
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(): JsonResponse
    {
        $list = $this->styleService->listStylePaginate();

        return response()->collection($list, new StyleTransformer, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->styleService->show($id);

        $this->styleService->destroy($item);

        return response(null, 200);
    }
}
