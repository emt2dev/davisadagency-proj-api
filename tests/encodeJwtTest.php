<?php
declare(strict_types=1);

require ("vendor/autoload.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class encodeJwtTest extends PHPUnit\Framework\TestCase
{
    public function testJwtIsSame(): void 
    {
        $key = 'example_key';
        $payload = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];

        $decodeKey = new Key($key, 'HS256');

        $jwt = JWT::encode($payload, $key, 'HS256');
        $decoded = JWT::decode($jwt, $decodeKey);
        $tokenAsArray = (array) $decoded;

        $this->assertSame($payload['iss'], $tokenAsArray['iss']);
    }
}
?>