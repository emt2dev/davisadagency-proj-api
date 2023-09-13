<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class jwtModel {
    private string $secretKey  = 'DavidDuronPortfolioProjectSecretKeyHireMe';
    public string $serverName = "domain.na.me";
    public $issuedAt;
    public $expire;
    public $payload = [];

    public function __construct() {
        $this->issuedAt   = new DateTimeImmutable();
        $this->expire     = $this->issuedAt->modify('+6 minutes')->getTimestamp();      // Add 6 mins
    }

    public function getSecretKey() {
        return $this->secretKey;
    }

    public function getServerName() {
        return $this->serverName;
    }

    public function issueToken(string $userEmail) {
        $this->payload = [
            'iat'  => $this->issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $this->serverName,                       // Issuer
            'nbf'  => $this->issuedAt->getTimestamp(),         // Not before
            'exp'  => $this->expire,                           // Expire
            'userEmail' => $userEmail, // User name
            'roles' => 'user'
        ];

        return JWT::encode($this->payload, $this->getSecretKey(), 'HS512');
    }
}

?>