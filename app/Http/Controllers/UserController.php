<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (User::where('name', $data['name'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "name" => [
                        "name already registered"
                    ]
                ]
            ], 400));
        }

        $user = new User($data);
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(201);
    }
}
