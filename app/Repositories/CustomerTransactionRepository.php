<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;
use App\Contracts\CrudInterface;
use App\Contracts\DBPreparableInterface;
use App\Models\CustomerTransaction;
use App\Models\Medication;
use App\Models\InventoryTransaction;

class CustomerTransactionRepository implements CrudInterface, DBPreparableInterface
{
    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);

        $query = CustomerTransaction::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $searched = '%' . $filter['search'] . '%';
                $query->where('transaction_type', 'like', $searched);
            });
        }

        if (!empty($filter['customer_id'])) {
            $query->where(function ($query) use ($filter) {
                $query->where('customer_id', '=', $filter['customer_id']);
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
            'order' => 'desc',

            'customer_id' => null,
        ];

        return array_merge($defaultArgs, $filterData);
    }

    public function getById(int $id): ?CustomerTransaction
    {
        $customerTransaction = CustomerTransaction::find($id);

        if (empty($customerTransaction)) {
            throw new Exception("Customer Transaction does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $customerTransaction;
    }

    public function purchaseMedication(array $data): ?CustomerTransaction
    {
        $data = $this->prepareForDB([...$data, 'transaction_type' => 'purchase']);
        // $medication_table = (new Medication)->getTable();
        $medication = Medication::find($data['medication_id']);
        $customerTransaction = CustomerTransaction::create($data);
        InventoryTransaction::create(array(
            'transaction_type' => 'dispense',
            'current_stock_quantity' => $medication->quantity,
            'quantity' => $data['quantity'],
            'medication_id' => $data['medication_id'],
            'created_by' => $data['created_by'],
            'remarks' => "Customer purchase : {$customerTransaction->id}"
        ));
        $medication->update(array(
            'quantity' => DB::raw("quantity - {$data['quantity']}")
        ));

        return $customerTransaction;
    }

    public function returnMedication(array $data): ?CustomerTransaction
    {
        $data = $this->prepareForDB([...$data, 'transaction_type' => 'return']);
        // $medication_table = (new Medication)->getTable();
        $medication = Medication::find($data['medication_id']);
        $customerTransaction = CustomerTransaction::create($data);
        InventoryTransaction::create(array(
            'transaction_type' => 'receive',
            'current_stock_quantity' => $medication->quantity,
            'quantity' => $data['quantity'],
            'medication_id' => $data['medication_id'],
            'created_by' => $data['created_by'],
            'remarks' => "Customer return : {$customerTransaction->id}"
        ));
        $medication->update(array(
            'quantity' => DB::raw("quantity + {$data['quantity']}")
        ));

        return $customerTransaction;
    }

    public function prepareForDB(array $data, ?CustomerTransaction $customerTransaction = null): array
    {
        if (empty($customerTransaction)) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }

    public function create(array $data): ?CustomerTransaction
    {
        return null;
    }

    public function update(int $id, array $data): ?CustomerTransaction
    {
        return null;
    }

    public function delete(int $id): ?CustomerTransaction
    {
        return null;
    }

}

