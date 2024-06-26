<?php

namespace App\Utils;

class MockIdTokenVerify
{

    protected $claims;

    public function __construct($claims)
    {
        $this->claims = $claims;
    }

    public static function verifyIdToken($idToken)
    {
        $decodedToken = array_map(function ($value) {
            return base64_decode($value);
        }, explode('.', $idToken));
        $decodedToken = $decodedToken[1] ?? null;
        if (!$decodedToken) {
            throw new \Exception('invalid id token');
        }
        $decodedToken = json_decode($decodedToken, true);
        $claims = new class($decodedToken) {
            protected $decodedToken;
            public function __construct(array $decodedToken)
            {
                $this->decodedToken = $decodedToken;
            }

            public function get(string $name)
            {
                return $this->decodedToken[$name] ?? null;
            }
        };
        return new self($claims);
    }

    public function claims()
    {
        return $this->claims;
    }
}
