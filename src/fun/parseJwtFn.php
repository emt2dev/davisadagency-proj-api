<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function parseJwtFn(string $userToken, jwtModel $serverToken) {
    $decodeKey = new Key($serverToken->getSecretKey(), 'HS512');

    $decodedToken = JWT::decode($userToken, $decodeKey);
    $tokenAsArray = (array) $decodedToken;

    return $tokenAsArray;
}

?>