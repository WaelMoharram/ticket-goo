<?php

namespace App\Http\Controllers\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Admin;
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

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return $this->unauthorizedApiResponse([], __('Invalid credentials.'));
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return $this->okApiResponse(['token' => $token], __('Login success.'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->okApiResponse([], __('Logout successful.'));
    }

    public function refreshToken(Request $request)
    {
        $admin = $request->user();

        // حذف التوكن الحالي
        $request->user()->currentAccessToken()->delete();

        // إنشاء توكن جديد
        $token = $admin->createToken('admin-token')->plainTextToken;

        return $this->okApiResponse([
            'token' => $token
        ], __('Token refreshed successfully.'));
    }

}
