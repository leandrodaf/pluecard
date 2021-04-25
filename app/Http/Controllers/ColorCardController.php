<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ColorTransformer;
use App\Services\ColorCardService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ColorCardController extends Controller
{
    public function __construct(
        private ColorCardService $colorCardService,
        AuthManager $auth
    ) {
        $this->colorCardService = $colorCardService;

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
            'name' => 'required|string',
            'matrix' => [
                'required',
                'unique:colors_cards',
                'regex: /#[a-zA-Z0-9]{6}|rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)|rgba\((\s*\d+\s*,){3}[\d\.]+\)|hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)|hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/',
            ],
        ]);

        $this->colorCardService->create($data);

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
            'name' => 'string',
            'matrix' => ['unique:colors_cards'.',id,'.$id, 'regex: /#[a-zA-Z0-9]{6}|rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)|rgba\((\s*\d+\s*,){3}[\d\.]+\)|hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)|hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/'],
        ]);

        $colorsCards = $this->colorCardService->show($id);

        $this->colorCardService->update($colorsCards, $data);

        return response(null, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $id): JsonResponse
    {
        $item = $this->colorCardService->show($id);

        return response()->item($item, new ColorTransformer, 200);
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(): JsonResponse
    {
        $list = $this->colorCardService->listColorCardPaginate();

        return response()->collection($list, new ColorTransformer, 200);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->colorCardService->show($id);

        $this->colorCardService->destroy($item);

        return response(null, 200);
    }
}
