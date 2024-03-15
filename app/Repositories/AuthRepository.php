<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthRepository
{
    public function login(array $data): array
    {
        Log::debug("AuthRepository->login");
        $user = $this->getUserByUsername($data['username']);

        if (!$user) {
            throw new Exception("Sorry, user does not exist.", 404);
        }

        if (!$this->isValidPassword($user, $data)) {
            throw new Exception("Sorry, password does not match.", 401);
        }

        $tokenInstance = $this->createAuthToken($user);

        return $this->getAuthData($user, $tokenInstance);
    }

    public function register(array $data): array
    {
        Log::debug("AuthRepository->register");
        $user = User::create($this->prepareDataForRegister($data));

        if (!$user) {
            throw new Exception("Sorry, user does not registered, Please try again.", 500);
        }

        $tokenInstance = $this->createAuthToken($user);

        return $this->getAuthData($user, $tokenInstance);
    }

    public function getUserByUsername(string $username): ?User
    {
        Log::debug("AuthRepository->getUserByUsername");
        return User::where('username', $username)->first();
    }

    public function isValidPassword(User $user, array $data): bool
    {
        Log::debug("AuthRepository->isValidPassword");
        return Hash::check($data['password'], $user->password);
    }

    public function createAuthToken(User $user): PersonalAccessTokenResult
    {
        Log::debug("AuthRepository->createAuthToken");
        return $user->createToken('authToken');
    }

    public function getAuthData(User $user, PersonalAccessTokenResult $tokenInstance): array
    {
        Log::debug("AuthRepository->getAuthData");
        return [
            'user'         => $user,
            'access_token' => $tokenInstance->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenInstance->token->expires_at)->toDateTimeString()
        ];
    }

    public function prepareDataForRegister(array $data): array
    {
        Log::debug("AuthRepository->prepareDataForRegister");
        return [
            'name'     => $data['name'],
            'username'    => $data['username'],
            'role'    => $data['role'],
            'password' => Hash::make($data['password']),
        ];
    }

    public function logout(): bool
    {
        Log::debug("AuthRepository->logout");
        $user = Auth::guard()->user();

        if (!$user) {
            throw new Exception("Sorry, user does not exist.", 404);
        }

        /*
        $user->tokens->each(function ($token) {
            $token->revoke();
        });
        */

        $user->token()->revoke();
        $user->token()->delete();
        return true;
    }
}
