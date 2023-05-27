<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    public function __invoke(LoginUserRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json(['message' => __('auth.failed')], Response::HTTP_BAD_REQUEST);
        }

        $token = $request->user()->createToken(
            'personal token',
            ['*'],
            now()->addMinutes(config('sanctum.expiration')))->plainTextToken;

        return response()->json(compact('token'));
    }
}
