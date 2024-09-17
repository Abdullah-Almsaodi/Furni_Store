<?php
// admin/classes/Service/AuthService.php
use Firebase\JWT\JWT;

class AuthService
{
    private static $key = 'your_jwt_secret_key';

    public static function generateToken($user)
    {
        $payload = [
            'iss' => "furni_store",
            'iat' => time(),
            'exp' => time() + 3600, // Token expires in 1 hour
            'user' => $user
        ];
        return JWT::encode($payload, self::$key);
    }

    public static function validateToken($token)
    {
        try {
            return JWT::decode($token, self::$key, array('HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}