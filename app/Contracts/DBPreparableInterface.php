<?php

namespace App\Contracts;

interface DBPreparableInterface
{
    public function prepareForDB(array $data): array;
}
