<?php

namespace App\Http\Controllers\V1\Website;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_code' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms_accepted' => ['required', 'boolean', 'in:1'],
            'subscribe_to_newsletter' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_code' => $request->phone_code,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'terms_accepted' => $request->terms_accepted,
            'subscribe_to_newsletter' => $request->subscribe_to_newsletter ?? false,
        ]);

        $token = $user->createToken('user-token')->plainTextToken;

        return $this->createdApiResponse([
            'token' => $token,
            'user' => $user,
        ], __('Account created successfully.'));
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorizedApiResponse([], __('Invalid credentials.'));
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return $this->okApiResponse([
            'token' => $token,
            'user' => $user,
        ], __('Login success.'));
    }
    public function socialLogin(Request $request, $provider)
    {
        $request->validate([
            'access_token' => ['required', 'string'],
        ]);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->access_token);
        } catch (\Exception $e) {
            return $this->unauthorizedApiResponse([], __('Invalid social token.'));
        }

        // نحاول نلاقي يوزر بالإيميل
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // مفيش يوزر => نعمل واحد جديد
            $user = User::create([
                'first_name' => $socialUser->getName() ?? 'First Name',
                'last_name' => '',
                'email' => $socialUser->getEmail(),
                'phone_code' => '',
                'phone' => '',
                'password' => Hash::make(uniqid()), // باسورد عشوائي
                'terms_accepted' => true, // بنفترض موافق
                'subscribe_to_newsletter' => false,
            ]);
        }

        // نطلعله توكن
        $token = $user->createToken('user-token')->plainTextToken;

        return $this->okApiResponse([
            'token' => $token,
            'user' => $user,
        ], __('Login success.'));
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
    public function me(Request $request)
    {
        $user = $request->user();

        return $this->okApiResponse([
            'user' => $user,
        ], __('User profile retrieved successfully.'));
    }
}
