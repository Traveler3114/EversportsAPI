<?php
require_once 'vendor/autoload.php';

$secret_key = "Fy-4My3y6I0mvI6NrrmuU5xkdcBMzt3Kh9v-Ak_ESrM=";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$input = json_decode(file_get_contents('php://input'), true);
$jwt = $input['jwt'] ?? null;
$action = $input['action'] ?? null;
if($action == "VerifyToken"){
    $decoded = VerifyToken($jwt);
    echo json_encode($decoded);
    exit;
}
function CreateToken($id, $email, $role)
{
    global $secret_key;

    $payload = [
        "iat" => time(),
        "exp" => time() + (60 * 60 * 2), // Token expires in 2 hours
        "data" => [
            "user_id" => $id,
            "email" => $email,
            "role" => $role // Add role to the payload
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
            "user_id" => strval($user_id),
            "email" => $email,
            "role" => $decoded->data->role
        ];
    } catch (Exception $e) {
        return [
            "status" => "error",
            "message" => "Invalid token: " . $e->getMessage()
        ];
    }
}

?>