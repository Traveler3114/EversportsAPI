<?php
require_once 'vendor/autoload.php';

$secret_key = "your_secret_key";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function CreateToken($id, $email)
{
    global $secret_key;


    $payload = [
        "iat" => time(),
        "exp" => time() + (60*60*2),
        "data" => [
            "user_id" => $id,
            "email" => $email,
        ]
    ];

    $jwt = JWT::encode($payload, $secret_key, 'HS256');
    return $jwt;
}



function VerifyToken($jwt) {
    try {
        global $secret_key;
        // Decode and validate the token
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

        // Access token data
        $user_id = $decoded->data->user_id;
        $email = $decoded->data->email;

        // Check if the token is expired
        if ($decoded->exp < time()) {
            return [
                "status" => "error",
                "message" => "Token has expired"
            ];
        }

        return [
            "status" => "success",
            "message" => "Token is valid",
            "user_id" => $user_id,
            "email" => $email
        ];
    } catch (Exception $e) {
        return [
            "status" => "error",
            "message" => "Invalid token: " . $e->getMessage()
        ];
    }
}

?>