<?php

namespace App\Repositories\Implementations;

use App\Helpers\PlaylistHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        try {
            $users = User::all();

            return $this->success("All users", $users);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    function fetchOne(string $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);

            if (!$user) return $this->error("No user found.", 404);

            return $this->success("User Detail", $user);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }

    function delete(string $id): JsonResponse
    {
        try{
            DB::beginTransaction();
            $userToDelete = User::query()->find($id);

            if(!$userToDelete) return $this->error('No user found', 400);

            UserHelper::deleteUserRelatedData($userToDelete);

            DB::commit();

            return $this->success('User deleted successfully.', $userToDelete, 200);
        }catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), 500);
        }
    }

    function update(string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
}
