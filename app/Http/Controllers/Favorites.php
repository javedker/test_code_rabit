<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Favorites extends Controller
{
    function index(): mixed
    {
        return view('pages.favorites.index');
    }

    function get(): mixed
    {
        return Http::withToken(signedUser('token'))->get(api('favorite'))->json();
    }
    function set(Request $request): mixed
    {
        $res = Http::withToken(signedUser('token'))->post(api('favorite/set'), $request->all())->json();
        return $res;
    }

    function remove(Request $request): mixed
    {
        $res = Http::withToken(signedUser('token'))->post(api('favorite/remove'), $request->all())->json();
        return $res;
    }
}
