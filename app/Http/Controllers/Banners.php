<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Banners extends Controller
{
    function get(): mixed
    {
        return Http::get(api('banners/get'),['for'=>'ecom'])->json();
    }
}
