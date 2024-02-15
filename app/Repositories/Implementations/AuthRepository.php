<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    use ResponseAPI;

    function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $loggedIn = Auth::attempt($credentials);

        if(!$loggedIn) return $this->error("Invalid credentials.", 401);

        $user = Auth::user();

        $abilities = ['end-user'];
        if($user->role->name == 'admin') {
            $abilities[] = 'admin';
        }
        $token = $user->createToken("Token", $abilities, now()->addWeek())->plainTextToken;

        $responseData = [
            "token" => $token,
            "user" => $user
        ];
        return $this->success("Logged in.", $responseData);
    }

    function register(RegisterRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();

            $requestUserData = $request->validated();
            $user = new User();

            $user->username = $requestUserData['username'];
            $user->email = $requestUserData['email'];
            $user->password = Hash::make($requestUserData['password']);

            $role = Role::query()->where('name', '=','end-user')->first();
            $user->role()->associate($role);
            $user->save();

            $user->settings()->create([
                "user_id" => $user->id,
                "explicit" => true
            ]);

            DB::commit();
            return $this->success("Registered.", null, 201);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), 500);
        }
    }
}
