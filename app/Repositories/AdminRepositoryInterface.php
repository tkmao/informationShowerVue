<?php

namespace App\Repositories;

interface AdminRepositoryInterface
{
    public function find($id);

    public function store($id, $requestArray);
}
