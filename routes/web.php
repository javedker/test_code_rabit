<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\Addresses;
use App\Http\Controllers\Articles;
use App\Http\Controllers\Authenticate;
use App\Http\Controllers\Banners;
use App\Http\Controllers\Cart;
use App\Http\Controllers\Checkout;
use App\Http\Controllers\Compares;
use App\Http\Controllers\Errors;
use App\Http\Controllers\Favorites;
use App\Http\Controllers\Home;
use App\Http\Controllers\Offers;
use App\Http\Middleware\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::controller(Home::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/home', 'index')->name('home.index');
    Route::get('/terms', 'terms')->name('home.terms');
    Route::get('/privacy-policy', 'privacy')->name('home.privacy');
    Route::get('/faqs', 'faqs')->name('home.faqs');
    Route::get('/about', 'about')->name('home.about');
    Route::get('/contact', 'contact')->name('home.contact');
    Route::post('/contact', 'contact')->name('home.contact.store');
});
Route::controller(Errors::class)->prefix('errors')->group(function () {
    Route::get('/{message?}', 'index')->name('errors.500');
    Route::get('/not-found', 'notFound')->name('errors.404');
    Route::get('/forbidden', 'forbidden')->name('errors.403');
    Route::get('/expired/{message?}', 'expired')->name('errors.419');
});


Route::controller(Articles::class)->prefix('shop')->group(function () {
    Route::get('/', 'index')->name('articles');
    Route::get('/details/{slug}', 'details')->name('articles.details');
    Route::get('get', 'get')->name('articles.get');
    Route::get('/search', 'search')->name('articles.search');
});

Route::controller(Offers::class)->prefix('offers')->group(function () {
    Route::get('get', 'get')->name('offers.get');
});
Route::controller(Banners::class)->prefix('banners')->group(function () {
    Route::get('get', 'get')->name('banners.get');
});


Route::controller(Authenticate::class)->group(function () {
    // Sign In
    Route::get('sign-in', 'signIn')->name('auth.sign-in');
    Route::post('sign-in', 'handleSignIn')->name('auth.handle-sign-in');

    // Sign Up
    Route::get('mobile', 'mobile')->name('auth.mobile');
    Route::get('verify', 'verify')->name('auth.verify');
    Route::get('sign-up', 'signUp')->name('auth.sign-up');
    Route::post('verify', 'verifyOtp')->name('auth.verify.otp');
    Route::post('send/otp', 'sendOtp')->name('auth.send.otp');
    Route::post('sign-up', 'handleSignUp')->name('auth.handle-sign-up');

    // Forgot Password
    Route::get('forgot-password', 'forgotPassword')->name('auth.forgot-password');
    Route::get('new-password/{token}', 'newPassword')->name('auth.new-password');
    Route::post('forgot-password', 'handleForgotPassword')->name('auth.handle-forgot-password');
    Route::post('new-password', 'handleNewPassword')->name('auth.handle-new-password');
});

Route::middleware(Auth::class)->group(function () {

    Route::controller(Articles::class)->prefix('shop')->group(function () {
        Route::post('set-stock-availability-notification', 'setStockAvailabilityNotification')->name('articles.set-stock-availability-notification');
    });

    Route::controller(Cart::class)->prefix('cart')->group(function () {
        Route::get('/', 'index')->name('cart');
        Route::post('set', 'set')->name('cart.set');
        Route::post('remove', 'remove')->name('cart.remove');
        Route::post('count', 'count')->name('cart.count');
        Route::post('get', 'get')->name('cart.get');
        Route::post('clear', 'clear')->name('cart.clear');
    });
    Route::controller(Favorites::class)->prefix('favorites')->group(function () {
        Route::get('/', 'index')->name('favorites');
        Route::post('set', 'set')->name('favorites.set');
        Route::post('remove', 'remove')->name('favorites.remove');
        Route::post('count', 'count')->name('favorites.count');
        Route::post('get', 'get')->name('favorites.get');
    });
    Route::controller(Compares::class)->prefix('compares')->group(function () {
        Route::get('/', 'index')->name('compares');
        Route::post('set', 'set')->name('compares.set');
        Route::post('remove', 'remove')->name('compares.remove');
        Route::post('count', 'count')->name('compares.count');
        Route::post('get', 'get')->name('compares.get');
    });
    Route::controller(Checkout::class)->prefix('checkout')->group(function () {
        Route::get('/', 'index')->name('checkout');
        Route::post('get', 'get')->name('checkout.get');
        Route::post('verify', 'verify')->name('checkout.verify');
        Route::post('create-order', 'createOrder')->name('checkout.create-order');
        Route::post('place-order', 'placeOrder')->name('checkout.place-order');
        Route::post('cash-receipt', 'createCashReceipt')->name('checkout.cash-receipt');
        Route::get('initiate-payment/{token}', 'initiatePayment')->name('checkout.initiate-payment');
        Route::post('proccess-payment', 'processPayment')->name('checkout.proccess-payment');
        Route::get('payment/{status}', 'paymentStatus')->name('checkout.payment-status');
    });

    Route::controller(Addresses::class)->prefix('addresses')->group(function () {
        Route::post('cities', 'cities')->name('addresses.cities');
        Route::post('store', 'store')->name('addresses.store');
        Route::post('get', 'get')->name('addresses.get');
        Route::post('set-primary', 'setPrimary')->name('addresses.set-primary');
    });

    Route::controller(Authenticate::class)->group(function () {
        Route::get('sign-out', 'signOut')->name('auth.sign-out');
    });

    Route::controller(Account::class)->prefix('account')->group(function () {
        Route::get('/', 'index')->name('account');
        Route::get('/addresses', 'addresses')->name('account.addresses');
        Route::get('/security', 'security')->name('account.security');
        Route::get('/orders', 'orders')->name('account.orders');
        Route::get('orders/view/{code}', 'viewOrder')->name('account.orders.view');
        Route::post('/orders/get', 'getOrders')->name('account.orders.get');
        Route::post('/reset-password', 'resetPassword')->name('account.reset-password');

        Route::post('/security/devices/get', 'getDevices')->name('account.security.devices.get');
        Route::post('/security/devices/revoke', 'revokeDevice')->name('account.security.devices.revoke');
        Route::post('/security/devices/revoke-others', 'revokeOtherDevices')->name('account.security.devices.revoke-others');
    });
});


Route::get('media/{img}', function ($img) {
    $img = str_replace('$', '/', $img);
    $mediaUrl = config('app.media_url');
    $res = Http::get("$mediaUrl/$img");
    if ($res->status() == 404) {
        abort(404);
    }
    $contentType = $res->header('Content-Type', '');
    if (str_contains($contentType, 'text/html') || str_starts_with(trim($res->body()), '<!DOCTYPE html') || str_starts_with(trim($res->body()), '<html')) {
        abort(404);
    }
    return response($res->body(), 200, [
        'Content-Type' => $contentType ?: 'application/octet-stream',
    ]);
})->name('media');
