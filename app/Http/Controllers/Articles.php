<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Articles extends Controller
{

    function index(): mixed
    {
        $filters = Http::get(api('article/flitters'))->json();
        return view('pages.articles.index', compact('filters'));
    }
    function get(Request $request): mixed
    {
        $limit = $request->has('limit') ? $request->limit : 12;
        $offset = $request->has('offset') ? $request->offset : 0; // corrected here
        $brands = $request->has('brands') ? $request->brands : [];
        $categories = $request->has('categories') ? $request->categories : [];
        $segments = $request->has('segments') ? $request->segments : [];
        $types = $request->has('types') ? $request->types : [];
        $specialPrice = $request->has('specialPrice') ? $request->specialPrice : 0;
        $inStock = $request->has('inStock') ? $request->inStock : 0;
        $sort = $request->has('sort') ? $request->sort : '';
        $search = $request->has('sort') ? $request->search : '';

        $params = [
            'limit' => $limit,
            'offset' => $offset,
            'brands' => $brands,
            'categories' => $categories,
            'segments' => $segments,
            'types' => $types,
            'inStock' => $inStock,
            'specialPrice' => $specialPrice,
            'sort' => $sort,
            'search' => $search,
        ];

        if (isUserSignedIn()) {
            $articles = Http::withToken(signedUser('token'))->get(api('article/get'), $params)->json();
        } else {
            $articles = Http::get(api('article/get'), $params)->json();
        }

        return $articles;
    }

    function search(Request $request): mixed
    {
        return Http::get(api('article/search'), $request->all())->json();
    }

    function details($slug): mixed
    {
        $article = isUserSignedIn() ? Http::withToken(signedUser('token'))->get(api("article/get/$slug"))->json() : Http::get(api("article/get/$slug"))->json();

        if (!$article['error']) {
            $article = $article['data'][0];
            return view('pages.articles.details', compact('article'));
        } else {
            return redirect()->route('errors.500', ['message' => $article['message']]);
        }
    }


    function setStockAvailabilityNotification(Request $request): mixed
    {
        return Http::withToken(signedUser('token'))->post(api('article/customer-stock-notification'), $request->all())->json();
    }
}
