<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Compares extends Controller
{
    function index(): mixed
    {
        $compares = Http::withToken(signedUser('token'))->get(api('compare'))->json();
        $compares = !$compares['error'] ? $compares['data'] : [];
        return view('pages.compares.index', compact('compares'));
    }

    function get(): mixed
    {
        return Http::withToken(signedUser('token'))->get(api('compare'))->json();
    }
    function set(Request $request): mixed
    {
        $res = Http::withToken(signedUser('token'))->post(api('compare/set'), $request->all())->json();
        return $res;
    }

    function remove(Request $request): mixed
    {
        $res = Http::withToken(signedUser('token'))->post(api('compare/remove'), $request->all())->json();
        return $res;
    }
}
