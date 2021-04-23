<?php

namespace App\Services;

use App\Models\Card\StyleButtonCard;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class StyleButtonService
{
    public function __construct(
        private StyleButtonCard $styleButtonCard
    ) {
    }

    /**
     * @param array $data
     * @return StyleButtonCard
     */
    public function create(array $data): StyleButtonCard
    {
        return $this->styleButtonCard->create($data);
    }

    /** @return Paginator  */
    public function listStyleButtonPaginate(): Paginator
    {
        return $this->styleButtonCard->simplePaginate(15);
    }

    /**
     * @param StyleButtonCard $styleButtonCard
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(StyleButtonCard $styleButtonCard, array $data): bool
    {
        return $styleButtonCard->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return StyleButtonCard
     */
    public function show(string $id): StyleButtonCard
    {
        return $this->styleButtonCard->where('id', $id)->firstOrFail();
    }

    /**
     * @param StyleButtonCard $styleButtonCard
     * @return void
     * @throws Exception
     */
    public function destroy(StyleButtonCard $styleButtonCard): void
    {
        $styleButtonCard->delete();
    }
}
