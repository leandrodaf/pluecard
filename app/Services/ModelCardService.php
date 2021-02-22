<?php

namespace App\Services;

use App\Models\Card\ModelCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

class ModelCardService
{
    public function __construct(
        private ModelCard $modelcard
    ) {
    }

    /**
     * Create new Card Model.
     *
     * @param array $data
     * @return ModelCard
     */
    public function create(array $data): ModelCard
    {
        $model = $this->modelcard->create($data);

        $model->styles()->sync($data['styles']);

        $model->colors()->sync($this->syncColors($data['colors']));

        $model->bodys()->create(['data' => $data['body']]);

        return $model;
    }

    private function syncColors(array $sync = []): array
    {
        $listSync = [];
        foreach ($sync as $color) {
            $listSync[$color['id']]['status'] = $color['status'];
        }

        return $listSync;
    }

    /**
     * List all Model Cards with paginate and search by name option.
     *
     * @param string|null $search
     * @return Paginator
     */
    public function listModelCardPaginate(string $search = null, array $styles = null): Paginator
    {
        $query = $this->modelcard->search($search);

        if (count($styles) > 0) {
            $query->whereHas('styles', function (Builder $hasQuery) use ($styles) {
                $hasQuery->whereIn('style_id', $styles);
            });
        }

        return $query->simplePaginate(15);
    }

    /**
     * Update Model Card.
     *
     * @param ModelCard $modelcard
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(ModelCard $modelcard, array $data): bool
    {
        $result = $modelcard->fillAndSave($data);

        if (! empty($data['styles'])) {
            $modelcard->styles()->sync($data['styles']);
        }

        if (! empty($data['colors'])) {
            $modelcard->colors()->sync(array_combine(Arr::pluck($data['colors'], 'id'), $data['colors']));
        }

        if (! empty($data['data'])) {
            $modelcard->body()->create(['data' => $data['data']]);
        }

        return $result;
    }

    /**
     * Show specific Model Card.
     *
     * @param string $id
     * @return ModelCard
     */
    public function show(string $id): ModelCard
    {
        return $this->modelcard->where('id', $id)->firstOrFail();
    }

    /**
     * Delete specific Model Card.
     *
     * @param ModelCard $modelcard
     * @return void
     * @throws Exception
     */
    public function destroy(ModelCard $modelcard): void
    {
        $modelcard->delete();
    }
}
