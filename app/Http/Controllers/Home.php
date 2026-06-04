<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Home extends Controller
{
    function index(): mixed
    {

        $articles = Http::get(api('article/get'), ['isNovelties' => 1])->json();
        return view('pages.index', compact('articles'));
    }


    function terms(): mixed
    {
        $terms = Http::get(api('settings/term-and-conditions'))->json();
        return view('pages.terms', compact('terms'));
    }
    function privacy(): mixed
    {
        $privacy = Http::get(api('settings/privacy-policy'))->json();
        return view('pages.privacy', compact('privacy'));
    }
    function faqs(): mixed
    {
        $faqs = Http::get(api('settings/faqs'))->json();
        return view('pages.faqs', compact('faqs'));
    }
    function about(): mixed
    {
        return view('pages.about');
    }
    function contact(): mixed
    {
        return view('pages.contact');
    }

    function contactSubmit(Request $request)
    {
        return true;
    }
}
