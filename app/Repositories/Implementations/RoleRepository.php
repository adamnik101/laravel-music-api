<?php

namespace App\Repositories\Implementations;

use App\Models\Role;
use App\Repositories\Interfaces\RoleInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;

class RoleRepository implements RoleInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $roles = Role::all();

        return $this->success('All roles', $roles);
    }

    function fetchOne(string $id): JsonResponse
    {
        $role = Role::query()->find($id);

        return $this->success('Fetch one role', $role);
    }

    function insert(array $data)
    {
        // TODO: Implement insert() method.
    }

    function delete(string $id): JsonResponse
    {
        // TODO: Implement delete() method.
    }

    function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
}
