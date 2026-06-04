<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


{
    function __construct() {}
    function signIn(): mixed
    {

        if (isUserSignedIn()) {
            return redirect()->route('home');
        }
        return view('pages.auth.sign-in');
    }

    function handleSignIn(Request $request): mixed
    {
        try {
            if (isUserSignedIn()) {
                return [
                    'error' => false,
                    'message' => 'You are already Signed In',
                    'data' => ['redirect' => route('home')]
                ];
            }

            $validate = validateForm($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);
            if ($validate['error']) {
                throw new Exception($validate['message']);
            }

            // ---- device info (frontend) ----
            $userAgent  = $request->userAgent() ?: 'unknown';
            $ip         = $request->ip();
            $deviceName = $request->input('device_name') // allow custom from client (e.g., "iPhone 15")
                ?: (Str::limit($userAgent, 60, '…') ?: 'device-' . Str::random(6));
            // uid sticky per browser: cookie or generate now
            $deviceUid  = $request->cookie('device_uid') ?? Str::uuid()->toString();
            Cookie::queue('device_uid', $deviceUid, 60 * 24 * 30); // 1 year

            // ---- login call ----
            $response = Http::post(api('auth/login'), [
                'type'       => 'email',
                'identity'   => $request->email,
                'password'   => $request->password,
                // pass device context so API can name the Sanctum token
                'device_name' => $deviceName,
                'device_uid' => $deviceUid,
                'user_agent' => $userAgent,
                'ip'         => $ip,
            ])->json();

            if (!empty($response['error'])) {
                throw new Exception($response['message'] ?? 'Login failed');
            }

            $user            = $response['data'];
            $user['token']   = $response['token']; // sanctum token (Bearer)

            // Persist session (same as your code)
            if ($request->remember) {
                Cookie::queue('user', encrypt($user), 60 * 24 * 30);
            } else {
                Cookie::queue('user', encrypt($user));
            }


            // ---- (optional but recommended) register/update this device on API ----
            // If your API supports it:
            try {
                Http::withToken($user['token'])
                    ->post(api('auth/devices/register'), [
                        'device_uid'  => $deviceUid,
                        'device_name' => $deviceName,
                        'user_agent'  => $userAgent,
                        'ip'          => $ip,
                    ]);
            } catch (\Throwable $e) {
                // non-blocking
            }

            $data['redirect'] = route('home');

            if (session()->has('isCheckoutProcceed')) {
                $bulk = Http::withToken($response['token'])->post(api('cart/bulk'), ['slugs' => getCartItems(true)])->json();
                clearCart();
                session()->forget('isCheckoutProcceed');
                $data['redirect'] = route('checkout');
            }
            return successResponse("Welcome " . $user['name'], $data);
        } catch (\Throwable $th) {
            return errorResponse($th);
        }
    }

    function mobile()
    {
        if (session()->has('signUpStage')) {
            return redirect()->route('auth.' . session('signUpStage'));
        }
        return view('pages.auth.mobile');
    }
    function verify(): mixed
    {
        if (session()->has('signUpStage')) {
            $stage = session('signUpStage');
            if ($stage == 'verify') {
                return view('pages.auth.verify');
            } else if ($stage == 'sign-up') {
                return redirect()->route('auth.sign-up');
            }
        }
        return redirect()->route('auth.mobile');
    }


    function signUp(): mixed
    {

        if (session()->has('signUpStage')) {
            $stage = session('signUpStage');
            if ($stage == 'otp') {
                return redirect()->route('auth.verify');
            } else if ($stage == 'sign-up') {
                return view('pages.auth.sign-up');
            }
        }
        return redirect()->route('auth.mobile');
    }

    function sendOtp(Request $request): mixed
    {
        try {
            $validate = validateForm($request->all(), [
                'mobile' => 'required'
            ]);
            if ($validate['error']) {
                throw new Exception($validate['message']);
            }

            $res = Http::post(api('auth/send-otp-mobile'), $request->all())->json();
            if (!$res['error']) {
                session()->put('signUpStage', 'verify');
                session()->put('mobile', $request->mobile);
                return successResponse($res['message'], ['redirect' => route('auth.verify')]);
            } else {
                throw new Exception($res['message']);
            }
        } catch (\Throwable $th) {
            return errorResponse($th);
        }
    }

    function verifyOtp(Request $request)
    {
        try {
            $validate = validateForm($request->all(), [
                'otp' => 'required'
            ]);
            if ($validate['error']) {
                throw new Exception($validate['message']);
            }

            $params = [
                'otp' => $request->otp,
                'mobile' => session()->get('mobile')
            ];
            $res = Http::post(api('auth/verify-otp'), data: $params)->json();
            if (!$res['error']) {
                session()->put('signUpStage', 'sign-up');
                if (!empty($res['data'])) {
                    session()->put('existingUser', $res['data']);
                }
                return successResponse($res['message'], ['redirect' => route('auth.sign-up')]);
            } else {
                throw new Exception($res['message']);
            }
        } catch (\Throwable $th) {
            return errorResponse($th);
        }
    }

    function handleSignUp(Request $request): mixed
    {
        $params = $request->all();
        $params['mobile'] = session()->get('mobile');

        if (session()->has('existingUser')) {
            $user = session('existingUser');
            $params['sap_number'] = (isset($user['sap_number']) && !empty($user['sap_number']))
                ? $user['sap_number']
                : "";

            $params['name'] = (isset($user['name']) && !empty($user['name']))
                ? $user['name']
                : $request->name;

            $params['email'] = (isset($user['email']) && !empty($user['email']))
                ? $user['email']
                : $request->email;

            $params['title'] = (isset($user['title']) && !empty($user['title']))
                ? $user['title']
                : $request->title;
        }
        $res = Http::post(api('auth/register'), data: $params)->json();
        if (!$res['error']) {
            session()->forget([
                'signUpStage',
                'mobile',
                'user'
            ]);
            $redirect = route('auth.sign-in');

            return successResponse($res['message'], ['redirect' => $redirect]);
        } else {
            return $res;
        }
    }

    function signOut(): mixed
    {
        Cookie::queue(Cookie::forget('user'));
        session()->forget('user');
        return redirect()->route('home')->with('success', 'You have been signed out successfully.');
    }


    function forgotPassword(): mixed
    {
        if (isUserSignedIn()) {
            return redirect()->route('home');
        }
        return view('pages.auth.forgot-password');
    }
    function newPassword(string $token, Request $request): mixed
    {
        if (isUserSignedIn()) {
            return redirect()->route('home');
        }
        $email = $request->email;
        $validate = Http::post(api('password/validate-token'), ['email' => $request->email, 'token' => $token])->json();
        if ($validate['error']) {
            return  redirect()->route('errors.419', ['message' => $validate['message']]);
        }
        return view('pages.auth.new-password', compact('token', 'email'));
    }
    function handleForgotPassword(Request $requests): mixed
    {
        return Http::post(api('password/email'), $requests->all())->json();
    }
    function handleNewPassword(Request $requests): mixed
    {
        $res = Http::post(api('password/reset'), $requests->all())->json();
        if (!$res['error']) {
            return successResponse($res['message'], ['redirect' => route('auth.sign-in')]);
        }
        return $res;
    }
}
