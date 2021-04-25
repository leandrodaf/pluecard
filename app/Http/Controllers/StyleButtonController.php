<?php

namespace App\Http\Controllers;

use App\Http\Transformers\StyleButtonsTransformer;
use App\Services\StyleButtonService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class StyleButtonController extends Controller
{
    public function __construct(
        private StyleButtonService $styleButtonService,
        AuthManager $auth
    ) {
        $this->styleButtonService = $styleButtonService;

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
            'name' => 'required|string|max:60|unique:style_buttons',
            'background' => 'required|base64_image',
        ]);

        $this->styleButtonService->create($data);

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
            'name' => 'string|max:60|unique:style_buttons'.',id,'.$id,
            'background' => 'base64_image',
        ]);

        $style = $this->styleButtonService->show($id);

        $this->styleButtonService->update($style, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $id): JsonResponse
    {
        $item = $this->styleButtonService->show($id);

        return response()->item($item, new StyleButtonsTransformer, 200);
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(): JsonResponse
    {
        $list = $this->styleButtonService->listStyleButtonPaginate();

        return response()->collection($list, new StyleButtonsTransformer, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->styleButtonService->show($id);

        $this->styleButtonService->destroy($item);

        return response(null, 200);
    }
}
