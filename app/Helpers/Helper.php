<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Mail\Message;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

function api($name)
{
    $url = config('app.api_url');
    return "$url/$name";
}


function formatePrice($price, $type = 'oman', $isHtml = false)
{
    if ($type == 'oman') {
        $price = number_format($price, 3);
        if ($isHtml) {
            return "<small class=''>OMR</small> $price";
        } else {
            return $price;
        }
    }
}


function getCategories()
{
    if (session()->has('categories')) {
        return session('categories');
    }

    $res = Http::get(api('article/categories'))->json();
    session()->put('categories', $res);
    session()->save();

    return $res;
}


/**
 * Return a standardized JSON error response.
 *
 * @param  \Throwable  $e
 * @param  int         $statusCode
 * @param  bool        $asArray     If true, returns an array instead of JsonResponse
 * @return array|JsonResponse
 */
function errorResponse(\Throwable $e, int $statusCode = 500, bool $asArray = false): array|JsonResponse
{
    $debug = Config::get('app.debug', false);
    $msg   = $e->getMessage();

    // If SQL error in production, log full and show generic
    if (str_starts_with($msg, 'SQLSTATE') && ! $debug) {
        Log::error('SQL Error: ' . $msg);
        $msg = 'Please try again later.';
    }

    // Build payload
    $payload = [
        'error'   => true,
        'message' => $msg,
        'status'  => $statusCode,
    ];

    if ($debug) {
        $payload += [
            'exception' => get_class($e),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'trace'     => $e->getTraceAsString(),
            'code'      => $e->getCode(),
        ];
    }

    return $asArray
        ? $payload
        : response()->json($payload, $statusCode);
}

/**
 * Return a standardized JSON success response.
 *
 * @param  string     $message
 * @param  mixed      $data
 * @param  array      $other
 * @param  int        $total
 * @param  string     $token
 * @param  bool       $asArray     If true, returns an array instead of JsonResponse
 * @return array|JsonResponse
 */
function successResponse(
    string $message,
    mixed  $data       = null,
    array  $other      = [],
    int    $total      = 0,
    string $token      = '',
    bool   $asArray    = false
): array|JsonResponse {
    $payload = [
        'error'   => false,
        'message' => $message,
        'data'    => $data,
        'total'   => $total,
        'token'   => $token,
        'other'   => $other,
    ];

    return $asArray
        ? $payload
        : response()->json($payload, 200);
}



/**
 * Validate an input array against rules & messages.
 *
 * @param  array  $params
 * @param  array  $rules
 * @param  array  $messages
 * @return array  ['error' => bool, 'message' => string]
 */
function validateForm(array $params, array $rules, array $messages = []): array
{
    $validator = Validator::make($params, $rules, $messages);

    if ($validator->fails()) {
        // Collect all error messages into one string
        $allErrors = $validator->errors()->all();
        return [
            'error'   => true,
            'message' => implode(' ', $allErrors),
        ];
    }

    return [
        'error'   => false,
        'message' => 'Form validated successfully.',
    ];
}



function isUserSignedIn()
{

    if (session()->has('user')) {
        return true;
    }
    if (!Cookie::has('user')) {
        return false;
    }

    try {
        $user = decrypt(Cookie::get('user'));
        return true;
    } catch (\Throwable $th) {
        // If decryption fails or invalid data
        return false;
    }
}

function signedUser($detail = '')
{
    if (!Cookie::has('user')) {
        return false;
    }

    try {
        $user = decrypt(Cookie::get('user'));


        return $detail && array_key_exists($detail, $user)
            ? $user[$detail]
            : $user;
    } catch (\Throwable $th) {
        // If decryption fails or invalid data
        return false;
    }
}


function sendMail(mixed $to, string $subject, string $body, mixed $cc = [])
{
    Mail::raw($body, function (Message $message) use ($subject, $to, $cc) {
        $message->to($to);
        if (!empty($cc)) {
            $message->cc($cc);
        }
        $message->subject(env('SYSTEM') . $subject);
    });
}


function getOperationsEmails(): mixed
{
    return [
        'pc.shah@kr.om',
        'j.kharva@kr.om',
        'j.ker@kr.om',
    ];
}


function setItemInCart(string $slug, int $qty)
{
    $res = Http::post(api('article/check'), ['slug' => $slug, 'qty' => $qty])->json();
    if ($res['error']) {
        return $res;
    }

    // get current cart or empty array
    $cart = Cookie::has('cart') ? json_decode(Cookie::get('cart'), true) : [];

    // update / add item
    $cart[$slug] = $qty;

    // save cart back to cookie (30 days)
    Cookie::queue('cart', json_encode($cart), 60 * 24 * 30);

    return [
        'error' => false
    ];
}

function countItemInCart()
{
    if (Cookie::has('cart')) {
        return count(json_decode(Cookie::get('cart'), 1));
    }
    return 0;
}


function getCartItems(bool $isRaw = false)
{
    if (Cookie::has('cart')) {
        $cart = json_decode(Cookie::get('cart'), 1);
        if ($isRaw) {
            return $cart;
        }
        $slugs = array_keys($cart);
        $res = Http::get(api('article/slugs'), ['slugs' => $slugs])->json();
        if (!$res['error']) {
            foreach ($res['data'] as $key => &$article) {
                if (isset($cart[$article['slug']])) {
                    $article['qty'] = $cart[$article['slug']];
                }
            }
            return $res;
        } else {
        }
    }
    return [];
}

function clearCart()
{
    return Cookie::queue(Cookie::forget('cart'));
}


function removeItemFromCart(string $slug)
{
    // get current cart or empty array
    $cart = Cookie::has('cart') ? json_decode(Cookie::get('cart'), true) : [];

    // if item exists in cart, remove it
    if (isset($cart[$slug])) {
        unset($cart[$slug]);
    }

    // save cart back to cookie (30 days)
    Cookie::queue('cart', json_encode($cart), 60 * 24 * 30);

    return true;
}

function addWorkingDaysExcludeFriSat(Carbon $start, int $days): Carbon
{
    $d = $start->copy();
    $added = 0;
    while ($added < $days) {
        $d->addDay();
        // 5 = Friday, 6 = Saturday in Carbon (0=Sun … 6=Sat)
        if (!in_array($d->dayOfWeek, [5, 6], true)) {
            $added++;
        }
    }
    return $d;
}


function setMedia($img)
{
    return str_replace('/', '$', $img);
}


function getOfferSettings(){
    if(session()->has('offers')){
        return session('offers');
    }else{
        $offers=Http::get(api('settings/offers'))->json();
        if(!$offers['error']){
            $offers=$offers['data'];
            if(!isset($offers['status'])){
                $offers['status']=false;
            }
            session()->put('offers',$offers);
            return $offers;
        }else{
            return ['status'=>false];
        }
    }
}