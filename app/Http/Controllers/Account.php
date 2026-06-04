<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class Account extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = signedUser();
    }
    function index(): mixed
    {

        return view('pages.account.index', ['user' => $this->user]);
    }

    function orders(): mixed
    {
        return view('pages.account.orders');
    }

    function getOrders(): mixed
    {
        return Http::withToken($this->user['token'])->get(api('order/get'))->json();
    }

    function viewOrder($code)
    {
        $order = Http::withToken($this->user['token'])->get(api('order/get/' . $code))->json();
        // return $order;
        return view('pages.account.order-view', compact('order'));
    }

    function security()
    {
        // just render the page; devices load via JS
        return view('pages.account.security');
    }

    function addresses()
    {
        return view('pages.account.addresses');
    }

    function resetPassword(Request $request): mixed
    {
        $res = Http::withToken(signedUser('token'))
            ->post(api('auth/reset-password'), $request->all())
            ->json();

        if (!$res['error']) {
            Cookie::queue(Cookie::forget('user'));
            return successResponse($res['message'], ['redirect' => route('auth.sign-in')]);
        } else {
            return $res;
        }
    }

    /*** NEW: proxy APIs for devices ***/

    // list devices
    function getDevices(Request $request): mixed
    {
        $res = Http::withToken(signedUser('token'))
            ->get(api('auth/devices'))
            ->json();

        return $res;
    }

    // revoke single device
    function revokeDevice(Request $request): mixed
    {
        $payload = $request->only('id', 'device_uid');
        $res = Http::withToken(signedUser('token'))
            ->post(api('auth/devices/revoke'), $payload)
            ->json();

        return $res;
    }

    // revoke all other devices (keep current)
    function revokeOtherDevices(Request $request): mixed
    {
        $payload = $request->only('device_uid');
        $res = Http::withToken(signedUser('token'))
            ->post(api('auth/devices/revoke-others'), $payload)
            ->json();

        return $res;
    }
}
