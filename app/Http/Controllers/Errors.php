<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Errors extends Controller
{
    function index(string $message = ''): mixed
    {
        return view('errors.500', compact('message'));
    }
    function notFound(): mixed
    {
        return view('errors.404');
    }
    function forbidden(): mixed
    {
        return view('errors.403');
    }
    function expired(string $message = ''): mixed
    {
        return view('errors.419', compact('message'));
    }
}
