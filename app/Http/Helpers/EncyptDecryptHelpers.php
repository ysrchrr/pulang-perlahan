<?php

function enc_id($value) {
    $key = substr(hash('sha256', env('CUSTOM_ENCRYPT_KEY')), 0, 16);
    $iv  = substr(hash('sha256', 'fixed_iv'), 0, 16);

    $encrypted = openssl_encrypt(
        (string) $value,
        'AES-128-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    if ($encrypted === false) {
        return '';
    }

    return base64url_encode($encrypted);
}

function dec_id($value) {
    $key = substr(hash('sha256', env('CUSTOM_ENCRYPT_KEY')), 0, 16);
    $iv  = substr(hash('sha256', 'fixed_iv'), 0, 16);

    $decoded = base64url_decode((string) $value);
    if ($decoded === false) {
        return null;
    }

    $decrypted = openssl_decrypt(
        $decoded,
        'AES-128-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return $decrypted === false ? null : $decrypted;
}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    $data = strtr($data, '-_', '+/');
    $padding = strlen($data) % 4;

    if ($padding) {
        $data .= str_repeat('=', 4 - $padding);
    }

    return base64_decode($data, true);
}
