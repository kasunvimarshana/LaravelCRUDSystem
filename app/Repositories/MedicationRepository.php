<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use App\Contracts\CrudInterface;
use App\Contracts\DBPreparableInterface;
use App\Models\Medication;

class MedicationRepository implements CrudInterface, DBPreparableInterface
{
    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);

        $query = Medication::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $searched = '%' . $filter['search'] . '%';
                $query->where('name', 'like', $searched);
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

    public function getById(int $id): ?Medication
    {
        $medication = Medication::find($id);

        if (empty($medication)) {
            throw new Exception("Medication does not exist.", Response::HTTP_NOT_FOUND);
        }

        // if ($medication->trashed()) {}

        return $medication;
    }

    public function create(array $data): ?Medication
    {
        $data = $this->prepareForDB($data);

        return Medication::create($data);
    }

    public function update(int $id, array $data): ?Medication
    {
        $medication = $this->getById($id);

        $updated = $medication->update($this->prepareForDB($data, $medication));

        if ($updated) {
            $medication = $this->getById($id);
        }

        return $medication;
    }

    public function delete(int $id): ?Medication
    {
        $medication = $this->getById($id);

        $deleted = $medication->delete();

        if (!$deleted) {
            throw new Exception("Medication could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $medication;
    }

    public function forceDelete(int $id): ?Medication
    {
        $medication = $this->getById($id);

        $this->deleteImage($medication->image_url);

        $deleted = $medication->forceDelete();

        if (!$deleted) {
            throw new Exception("Medication could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $medication;
    }

    public function restore(int $id): ?Medication
    {
        $medication = $this->getById($id);

        $restored = $medication->restore();

        if (!$restored) {
            throw new Exception("Medication could not be restored.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $medication;
    }

    public function prepareForDB(array $data, ?Medication $medication = null): array
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->createUniqueSlug($data['name']);
        }

        if (!empty($data['image'])) {
            if (!is_null($medication)) {
                $this->deleteImage($medication->image_url);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        if (empty($medication)) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }

    private function createUniqueSlug(string $name): string
    {
        return Str::slug(substr($name, 0, 80)) . '-' . time();
    }

    private function uploadImage($image): string
    {
        $imageName = time() . '.' . $image->extension();
        $image->storePubliclyAs('public', $imageName);
        return $imageName;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if(!empty($imageUrl)) {
            $imageName = ltrim(strstr($imageUrl, 'storage/'), 'storage/');
            if(!empty($imageName) && Storage::exists('public/' . $imageName)) {
                Storage::delete('public/' . $imageName);
            }
        }
    }
}
