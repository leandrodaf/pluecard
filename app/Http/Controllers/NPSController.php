<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Http\Transformers\NPSResultTransformer;
use App\Services\NPSService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class NPSController extends Controller
{
    public function __construct(
        private NPSService $NPSService,
        private AuthManager $auth
    ) {
    }

    public function rating(Request $request): Response
    {
        $npsModels = implode(',', array_keys(config('NPS.relationships')));

        $data = $this->validate($request, [
            'rating' => 'required|integer|in:1,2,3,4,5,6,7,8,9,10',
            'relationships' => 'required|string|in:'.$npsModels,
            'relationId' => 'required_unless:relationships,NOT_RELATION|integer',
        ]);

        try {
            DB::beginTransaction();

            $this->NPSService->rating(
                $this->auth->user(),
                $data['relationships'],
                $data['rating'],
                $data['relationId'] ?? null
            );

            DB::commit();
        } catch (Exception $exec) {
            DB::rollBack();

            throw $exec;
        }

        return response(null, 201);
    }

    public function index(Request $request, string $metricId): JsonResponse
    {
        Gate::authorize('admin', $this->auth->user());

        throw_unless(
            array_key_exists($metricId, config('NPS.metrics')),
            ValidatorException::class,
            ['metrics' => "The '$metricId' metrics does not exist"]
        );

        $startDate = $request->input('startDate') ?
            Carbon::create($request->input('startDate')) : Carbon::now()->subMonth();

        $endDate = $request->input('endDate') ?
            Carbon::create($request->input('endDate')) : Carbon::now();

        $entity = $request->input('entity', 'NOT_RELATION');

        throw_unless(
            array_key_exists($entity, config('NPS.relationships')),
            ValidatorException::class,
            ['metrics' => "The entity '$entity' does not exist"]
        );

        $item = $this->NPSService->metric(
            $metricId,
            $startDate,
            $endDate,
            $entity,
            $request->input('entityId', null)
        );

        return response()->item($item, new NPSResultTransformer);
    }
}
