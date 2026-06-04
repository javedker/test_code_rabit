<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth as LaravelAuth;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public $expludedRoutes = [
        'cart',
        'cart.set',
        'cart.count',
        'cart.get',
        'cart.remove',
        'cart.clear',
    ];
    public function handle(Request $request, Closure $next): Response
    {
        if (!isUserSignedIn()) {
            $routeName = request()->route()->getName();
            if (!in_array($routeName, $this->expludedRoutes)) {
                if ($request->isMethod('post')) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Kindly Sign In to continue!',
                        'total' => 0,
                        'redirect' => route('auth.sign-in')
                    ], 401);
                } else {

                    if ($routeName == 'checkout') {
                        if (countItemInCart()) {
                            session()->put('isCheckoutProcceed', true);
                        }
                    }

                    return redirect()->route('auth.sign-in');
                }
            }
        }

        return $next($request);
    }
}
