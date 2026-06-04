<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Addresses extends Controller
{
    function cities(): mixed
    {
        return Http::get(api('country/cities'))->json();
    }


    function store(Request $request)
    {
        $params = $request->all();
        $res = Http::withToken(signedUser('token'))->post(api('addresses/store'), $params)->json();
        if (!$res['error']) {
            return successResponse($res['message'], $res['data']);
        } else {
            return $res;
        }
    }
    function setPrimary(Request $request)
    {
        $params = $request->all();
        $res = Http::withToken(signedUser('token'))->post(api('addresses/set-primary'), $params)->json();
        if (!$res['error']) {
            return successResponse($res['message'], $res['data']);
        } else {
            return $res;
        }
    }

    function get(): mixed
    {
        return  Http::withToken(signedUser('token'))->get(api('addresses'))->json();
    }
}
