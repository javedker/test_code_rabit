<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Cart extends Controller
{
    function index(): mixed
    {
        return view('pages.cart.index');
    }

    function get()
    {
        if (isUserSignedIn()) {
            $res = Http::withToken(signedUser('token'))->get(url: api('cart/get'))->json();
            return $res;
        } else {
            $cart = getCartItems();
            if (!empty($cart)) {
                return $cart;
            } else {
                return ['error' => true, 'Your cart list empty'];
            }
        }
    }
    function set(Request $request): mixed
    {
        try {
            $validate = validateForm($request->all(), [
                'slug' => 'required',
                'qty' => 'required|numeric'
            ]);
            if ($validate['error']) {
                throw new Exception($validate['message']);
            }

            if (isUserSignedIn()) {
                $res = Http::withToken(signedUser('token'))->post(api('cart/insert'), $request->all())->json();
                if ($res['error']) {
                    throw new Exception($res['message']);
                }
                return $res;
            } else {
                $item = setItemInCart($request->slug, $request->qty);
                if (!$item['error']) {
                    return successResponse('Item has been updated in cart', ['qty' => $request->qty]);
                } else {
                    throw new Exception(isset($item['message']) ? $item['message'] : 'Failed to update in cart');
                }
            }
        } catch (\Throwable $th) {
            return errorResponse($th);
        }
    }
    function remove(Request $request): mixed
    {
        try {
            $validate = validateForm($request->all(), [
                'slug' => 'required',
            ]);
            if ($validate['error']) {
                throw new Exception($validate['message']);
            }

            if (isUserSignedIn()) {
                $res = Http::withToken(signedUser('token'))->post(api('cart/remove'), $request->all())->json();
                if ($res['error']) {
                    throw new Exception($res['message']);
                }
                return $res;
            } else {
                if (removeItemFromCart($request->slug)) {
                    return successResponse('Item has been removed');
                } else {
                    throw new Exception('Failed to remove Item');
                }
            }
        } catch (\Throwable $th) {
            return errorResponse($th);
        }
    }

    function count(): mixed
    {
        if (isUserSignedIn()) {
            $res = Http::withToken(signedUser('token'))->get(url: api('cart/count'))->json();
            if (!$res['error']) {
                return $res['total'];
            } else {
                return 0;
            }
        } else {
            return countItemInCart();
        }
    }

    function clear(): mixed
    {
        try {
            if (isUserSignedIn()) {
                $cart = Http::withToken(signedUser('token'))->post(api('cart/clear'))->json();
                if ($cart['error']) {
                    throw new Exception($cart['message']);
                }
            } else {
                clearCart();
                return successResponse('Cart cleared successfully');
            }
            return successResponse($cart['message']);
        } catch (\Throwable $th) {
            return errorResponse($th);
        }
    }
}
