<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Contracts\CrudInterface;
use App\Contracts\DBPreparableInterface;
use App\Models\Customer;

class CustomerRepository implements CrudInterface, DBPreparableInterface
{
    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);

        $query = Customer::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $searched = '%' . $filter['search'] . '%';
                $query->where('name', 'like', $searched)
                    ->orWhere('phone', 'like', $searched)
                    ->orWhere('email', 'like', $searched);
            });
        }

        // $query = $query->withTrashed();

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

    public function getById(int $id): ?Customer
    {
        $customer = Customer::find($id);

        if (empty($customer)) {
            throw new Exception("Customer does not exist.", Response::HTTP_NOT_FOUND);
        }

        // if ($customer->trashed()) {}

        return $customer;
    }

    public function create(array $data): ?Customer
    {
        $data = $this->prepareForDB($data);

        return Customer::create($data);
    }

    public function update(int $id, array $data): ?Customer
    {
        $customer = $this->getById($id);

        $updated = $customer->update($this->prepareForDB($data, $customer));

        if ($updated) {
            $customer = $this->getById($id);
        }

        return $customer;
    }

    public function delete(int $id): ?Customer
    {
        $customer = $this->getById($id);
        dump($customer);

        $deleted = $customer->delete();

        if (!$deleted) {
            throw new Exception("Customer could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $customer;
    }

    public function forceDelete(int $id): ?Customer
    {
        $customer = $this->getById($id);

        $deleted = $customer->forceDelete();

        if (!$deleted) {
            throw new Exception("Customer could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $customer;
    }

    public function restore(int $id): ?Customer
    {
        $customer = $this->getById($id);

        $restored = $customer->restore();

        if (!$restored) {
            throw new Exception("Customer could not be restored.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $customer;
    }

    public function prepareForDB(array $data, ?Customer $customer = null): array
    {
        if (empty($customer)) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }
}
