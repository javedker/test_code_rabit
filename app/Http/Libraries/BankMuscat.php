<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Http;

class BankMuscat
{

    public static function initiateTransaction($request)
    {
        $access_code = env('ACCESS_CODE');
        $data = '';
        $request['redirect_url'] = route('checkout.proccess-payment');
        $request['cancel_url'] =  route('checkout.proccess-payment');
        foreach ($request as $key => $value) {
            $data .= $key . '=' . urlencode($value) . '&';
        }


        $encryptedData = BankMuscat::encryptPayment($data);
        return [
            'error' => false,
            'message' => 'Success',
            'enRequest' => $encryptedData,
        ];
    }

    //? generate CSRF Token;
    public static function encryptPayment(string $data)
    {
        $method = "AES-256-GCM";
        $key = env('WORKING_KEY');
        $initVector = openssl_random_pseudo_bytes(length: 16);
        $openMode = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $initVector, $tag);
        return bin2hex($initVector) . bin2hex($openMode . $tag);
    }

    public static function decryptPayment(string $response)
    {
        $method = 'AES-256-GCM';
        $key = env('WORKING_KEY');
        $response = hex2bin($response);
        $iv_len = $tag_length = 16;
        $iv = substr($response, 0, $iv_len);
        $tag = substr($response, -$tag_length, $iv_len);
        $ciphertext = substr($response, $iv_len, -$tag_length);
        $rcvdString = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        $dataArr = array();
        foreach ($decryptValues as $key => $value) {
            $orderdata = explode('=', $value);
            $dataArr[$orderdata[0]] = $orderdata[1];
        }
        return $dataArr;
    }
}
