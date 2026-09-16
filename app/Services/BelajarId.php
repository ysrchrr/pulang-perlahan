<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BelajarId
{
    public static function get($type, $email = null)
    {
        $url = self::setUrl($type);

        return self::call($url, $email);
    }

    public static function setUrl($type)
    {
        return "https://api.belajar.id/belajar-id/account-lookup/v1/lookup/{$type}/email/";
    }

    public static function call($url, $email)
    {
        $auth = config('services.belajar_id.auth') ?: env('BELAJAR_ID_AUTH');

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => trim((string) $auth),
            ])
            ->get($url . rawurlencode($email));

        return json_decode($response->body());
    }
}
