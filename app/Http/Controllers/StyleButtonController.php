<?php

namespace App\Http\Controllers;

use App\Http\Transformers\StyleButtonTransformer;
use App\Services\StyleButtonService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\MassAssignmentException;
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
     * @return Response
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
     * @return Response
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
     * @return Response
     * @throws BindingResolutionException
     */
    public function show(string $id): Response
    {
        $item = $this->styleButtonService->show($id);

        return $this->itemResponse($item, new StyleButtonTransformer, 200);
    }

    /**
     * @return Response
     * @throws BindingResolutionException
     */
    public function index(): Response
    {
        $list = $this->styleButtonService->listStyleButtonPaginate();

        return $this->paginateResponse($list, new StyleButtonTransformer, 200);
    }

    /**
     * @param string $id
     * @return Response
     * @throws Exception
     */
    public function destroy(string $id): Response
    {
        $item = $this->styleButtonService->show($id);

        $this->styleButtonService->destroy($item);

        return response(null, 200);
    }
}
