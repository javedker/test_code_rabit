<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Offers extends Controller
{
    function get(): mixed
    {
        return Http::get(api('offers/get'))->json();
    }
}
