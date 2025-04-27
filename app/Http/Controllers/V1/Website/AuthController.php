<?php

namespace App\Http\Controllers\V1\Website;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->unauthorizedApiResponse([], __('Invalid credentials.'));
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return $this->okApiResponse(['token' => $token], __('Login success.'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->okApiResponse([], __('Logout successful.'));
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();

        // حذف التوكن الحالي
        $request->user()->currentAccessToken()->delete();

        // إنشاء توكن جديد
        $token = $user->createToken('user-token')->plainTextToken;

        return $this->okApiResponse([
            'token' => $token
        ], __('Token refreshed successfully.'));
    }
}
