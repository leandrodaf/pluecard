<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ButtonTransformer;
use App\Services\ButtonService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ButtonController extends Controller
{
    public function __construct(
        private ButtonService $buttonService,
        AuthManager $auth
    ) {
        $this->buttonService = $buttonService;

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
            'name' => 'required|string|max:60|unique:buttons_card',
            'background' => 'required|base64_image',
            'style_buttons_id' => 'required|integer|exists:style_buttons,id',
        ]);

        $this->buttonService->create($data);

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
            'name' => 'string|max:60|unique:buttons_card'.',id,'.$id,
            'background' => 'base64_image',
            'style_buttons_id' => 'required|integer|exists:style_buttons,id',
        ]);

        $style = $this->buttonService->show($id);

        $this->buttonService->update($style, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $id): JsonResponse
    {
        $item = $this->buttonService->show($id);

        return response()->item($item, new ButtonTransformer, 200);
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(): JsonResponse
    {
        $list = $this->buttonService->listButtonPaginate();

        return response()->collection($list, new ButtonTransformer, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->buttonService->show($id);

        $this->buttonService->destroy($item);

        return response(null, 200);
    }
}
