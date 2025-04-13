<?php
require_once 'vendor/autoload.php';

$secret_key = "your_secret_key";

use Firebase\JWT\JWT;
function CreateToken($id,$email)
{
    global $secret_key;

    $payload=[
            "iat" => time(), // Issued at: time when the token is generated
            "exp" => time() + (60*60*24), // Expiration time: 1 day from now
            "data" => [
                "user_id" => $id, // User ID from the database
                "email" => $email, // User email from the database
            ]
            ];
        
          // Use a secure key

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