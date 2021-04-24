<?php

namespace App\Services;

use App\Models\Card\ButtonCard;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class ButtonService
{
    public function __construct(
        private ButtonCard $buttonCard
    ) {
    }

    /**
     * @param array $data
     * @return ButtonCard
     */
    public function create(array $data): ButtonCard
    {
        return $this->buttonCard->create($data);
    }

    /** @return Paginator  */
    public function listButtonPaginate(): Paginator
    {
        return $this->buttonCard->simplePaginate(15);
    }

    /**
     * @param ButtonCard $buttonCard
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(ButtonCard $buttonCard, array $data): bool
    {
        return $buttonCard->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return ButtonCard
     */
    public function show(string $id): ButtonCard
    {
        return $this->buttonCard->where('id', $id)->firstOrFail();
    }

    /**
     * @param ButtonCard $buttonCard
     * @return void
     * @throws Exception
     */
    public function destroy(ButtonCard $buttonCard): void
    {
        $buttonCard->delete();
    }
}
