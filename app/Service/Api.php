<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class Api
{

    public static function apiPost($url, $dataSend = array())
    {
        $user = session()->get('user');
        $url_connect = env('API_URL') . $url;
        if ($user && $user['web_token']) {
            $response = Http::withHeaders([
                'Authorization' => $user['web_token']
            ])->get($url_connect, $dataSend);
        } else {
            $response = Http::post($url_connect, $dataSend);
        }

        $is_response_ok = $response->ok();
        if ($is_response_ok) {
            self::getResultResponse($response);
        }
        return null;
    }

    public static function apiGet($url, $dataSend = array())
    {
        $user = session()->get('user');
        $url_connect = env('API_URL') . $url;
        if ($user && $user['web_token']) {
            $response = Http::withHeaders([
                'Authorization' => $user['web_token']
            ])->get($url_connect, $dataSend);
        } else {
            $response = Http::get($url_connect, $dataSend);
        }
        $response = Http::get($url_connect);
        $is_response_ok = $response->ok();
        if ($is_response_ok) {
            self::getResultResponse($response);
        }
        return null;
    }

    public static function apiPost1($url, $dataSend = array())
    {
        $user = session()->get('user');
        $url_connect = env('API_URL') . $url;
        $response = Http::post($url_connect, $dataSend);
        $result = json_decode($response->body(), true);
        echo "<pre>";
        echo '$result: ';
        print_r($result);
        echo "</pre>";
        die();
    }

    public static function apiGet1($url, $dataSend = array())
    {
        $user = session()->get('user');
        $url_connect = env('API_URL') . $url;
        $response = Http::get($url_connect, $dataSend);
        $result = json_decode($response->body(), true);
        echo "<pre>";
        echo '$result: ';
        print_r($result);
        echo "</pre>";
        die();
    }

    public function getResultResponse($response) {
        $result = array();
        $result = json_decode($response->body(), true);
        if ($result['status'] && $result['status'] == 200) {
            return $result;
        } else {
            session()->forget('user');
            header('Location'. route('auth_login'));
            exit();
        }
    }

}
