<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Contracts\CrudInterface;
use App\Contracts\DBPreparableInterface;
use App\Models\InventoryTransaction;

class InventoryTransactionRepository implements CrudInterface, DBPreparableInterface
{
    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);

        $query = InventoryTransaction::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $searched = '%' . $filter['search'] . '%';
                $query->where('transaction_type', 'like', $searched);
            });
        }

        return $query->paginate($filter['perPage'], $filter['columns'], $filter['pageName'], $filter['page']);
    }

    public function getFilterData(array $filterData): array
    {
        $defaultArgs = [
            'perPage' => null,
            'columns' => ['*'],
            'pageName' => 'page',
            'page' => null,

            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];

        return array_merge($defaultArgs, $filterData);
    }

    public function getById(int $id): ?InventoryTransaction
    {
        $inventoryTransaction = InventoryTransaction::find($id);

        if (empty($inventoryTransaction)) {
            throw new Exception("Inventory Transaction does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $inventoryTransaction;
    }

    public function create(array $data): ?InventoryTransaction
    {
        $data = $this->prepareForDB($data);

        return InventoryTransaction::create($data);
    }

    public function update(int $id, array $data): ?InventoryTransaction
    {
        $inventoryTransaction = $this->getById($id);

        $updated = $inventoryTransaction->update($this->prepareForDB($data, $inventoryTransaction));

        if ($updated) {
            $inventoryTransaction = $this->getById($id);
        }

        return $inventoryTransaction;
    }

    public function delete(int $id): ?InventoryTransaction
    {
        $inventoryTransaction = $this->getById($id);

        $deleted = $inventoryTransaction->delete();

        if (!$deleted) {
            throw new Exception("Inventory Transaction could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $inventoryTransaction;
    }

    public function prepareForDB(array $data, ?InventoryTransaction $inventoryTransaction = null): array
    {
        if (empty($inventoryTransaction)) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }

}
