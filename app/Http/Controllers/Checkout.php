<?php

namespace App\Http\Controllers;

use App\Http\Libraries\BankMuscat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Checkout extends Controller
{
    function index(): mixed
    {
        // return Http::withToken(signedUser('token'))->get(api('checkout/get'))->json();
        return view('pages.checkout.index');
    }

    function get(): mixed
    {
        return Http::withToken(signedUser('token'))->get(api('checkout/get'))->json();
    }
    public function placeOrder(Request $request)
    {
        try {


            $token = signedUser('token');

            // 2) Create order
            $createOrder = Http::withToken($token)
                ->post(api('checkout/create-order'), [
                    'addressId'              => $request->addressId,
                    'deliveryType'           => 'home',
                    'is_terms_and_condition' => 1,
                    'tax'                    => $request->tax,
                ])
                ->json();
            if (! empty($createOrder['error'])) {
                throw new Exception($createOrder['message']);
            }

            $data = [
                'order_id' => $createOrder['data']['order_id'],
                'tid' => $createOrder['data']['order_id'],
                'merchant_id' => env('MERCHANT_ID'),
                'amount' => $createOrder['data']['total'],
                'currency' => 'OMR'
            ];

            $initiatePayment = BankMuscat::initiateTransaction($data);

            if ($initiatePayment['error']) {
                throw new Exception($initiatePayment['message']);
            }
            if (!session()->has('user')) {
                session()->put('user', Cookie::get('user'));
            }
            return successResponse('Success', data: ['redirect' => route('checkout.initiate-payment', ['token' => $initiatePayment['enRequest']]), 'disabledToast' => true]);

            // All steps succeeded: return the final payload
        } catch (\Exception $e) {
            // Any API error or thrown exception ends up here
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    function initiatePayment($token): mixed
    {
        return view('pages.checkout.payment', compact('token'));
    }

    function processPayment(Request $request): mixed
    {
        try {

            $token = decrypt(session('user'))['token'];
            $orderId = $request->orderNo;
            $response = BankMuscat::decryptPayment($request->encResp);
            $status = !isset($request->orderStatus) ? $response['order_status'] : $request->orderStatus;
            if (in_array($status, ['Success', 'Shipped', 'Successful'])) {



                $verify = Http::withToken($token)
                    ->post(api('customer/verify'))
                    ->json();
                if (! empty($verify['error'])) {
                    throw new Exception($verify['message']);
                }

                // // 3) Create cash receipt
                // $txnId = Str::upper(Str::random(10));
                $receipt = Http::withToken($token)
                    ->post(api('checkout/cash-receipt'), [
                        'txn_id' => $response['bank_ref_no'],
                        'order_id' => $orderId,
                        'amount' => $response['mer_amount'],
                        'status' => $status,
                    ])
                    ->json();
                if ($receipt['error']) {
                    throw new Exception($receipt['message']);
                }

                // // 4) Place sales order
                $place = Http::withToken($token)
                    ->post(api('checkout/place-order'), [
                        'orderNo'     => $orderId,
                        'orderStatus' => $status,
                        'response' => $response
                    ])
                    ->json();
                if (! empty($place['error'])) {
                    throw new Exception($place['message']);
                }

                return redirect()->route('account.orders.view', ['code' => $orderId]);
            } else {
                $orderId = $request->orderNo;
                $response = BankMuscat::decryptPayment($request->encResp);
                $place = Http::withToken($token)
                    ->post(api('checkout/place-order'), [
                        'orderNo'     => $orderId,
                        'orderStatus' => $status,
                        'response' => $response
                    ])
                    ->json();

                if (! empty($place['error'])) {
                    throw new Exception($place['message']);
                }
                return redirect()->route('checkout');
            }
        } catch (\Throwable $th) {
            $status = !isset($request->orderStatus) ? $response['order_status'] : $request->orderStatus;
            if (in_array($status, ['Success', 'Shipped', 'Successful'])) {
                $user = decrypt(session('user'));
                $username = $user['name'];
                $email = $user['email'];
                $mobile = $user['mobile'];
                $message = "Dear Team,

The payment for Order No: {$request->orderNo} was successful. 
However, SAP Customer Creation, Cash Receipt, and Sales Order Creation have failed. 

Please contact the customer immediately using the details below:

Name   : {$username}
Email  : {$email}
Mobile : {$mobile}";

                sendMail(getOperationsEmails(), config('app.mail_prefix') . "URGENT: SO creation failed for $request->orderNo", $message);

                $place = Http::withToken($token)
                    ->post(api('checkout/place-order'), [
                        'orderNo'     => $orderId,
                        'orderStatus' => 'payment_success',
                        'response' => $response
                    ])
                    ->json();
            }
            return redirect()->route('checkout.payment-status', ['status' => !isset($request->orderStatus) ? $response['order_status'] : $request->orderStatus]);
        }
    }

    function paymentStatus($status)
    {

        return view('pages.checkout.status', compact('status'));
    }
}
