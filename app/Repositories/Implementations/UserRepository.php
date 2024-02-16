<?php

namespace App\Repositories\Implementations;

use App\Helpers\PlaylistHelper;
use App\Helpers\UserHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\String\u;

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
            Log::error($exception->getMessage());
            return $this->error($exception->getMessage(), 500);
        }
    }

    function insert(array $data)
    {
        // TODO: Implement insert() method.
    }
    function delete(string $id): JsonResponse
    {
        try{
            DB::beginTransaction();
            $userToDelete = User::query()->find($id);

            if(!$userToDelete) return $this->error('No user found', 400);
            if ($userToDelete->id == Auth::user()->getAuthIdentifier()) return $this->error('Cannot delete your own account', 422);

            UserHelper::deleteUserRelatedData($userToDelete);

            UserHelper::deleteUser($userToDelete);

            DB::commit();

            return $this->success('User deleted successfully.', $userToDelete, 200);
        }catch (\Exception $exception) {
            DB::rollBack();
            Log::critical($exception->getMessage());
            return $this->error("Server error", 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }

    public function fetchUserLikedTracks(): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $tracks = $user->likedTracks()->with('owner', 'features', 'album')->get();

        return $this->success('User liked tracks', $tracks);
    }
}
