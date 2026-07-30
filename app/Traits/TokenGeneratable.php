<?php

namespace App\Traits;

use DateTimeImmutable;
use Exception;
use Illuminate\Support\Str;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\Validator;

trait TokenGeneratable {
    private function generateOTP(): string
    {
        return Str::upper(Str::random(6));
    }

    private function generateJwt(array $claims, $expiresInMinute = 15): string
    {
        $tokenBuilder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $algorithm = new Sha256();
        $signingKey = InMemory::plainText(env('JWT_SECRET'));

        $now = new DateTimeImmutable();

        $tokenBuilder =  $tokenBuilder->issuedBy(config('app.url'))  // iss
            ->identifiedBy(Str::uuid())             // jti
            ->issuedAt($now) // iat
            ->expiresAt($now->modify("+{$expiresInMinute} minute")); // exp

        foreach ($claims as $key => $value) {
            $tokenBuilder = $tokenBuilder->withClaim($key, $value);
        }

        return $tokenBuilder->getToken($algorithm, $signingKey)->toString();
    }

    private function verifyJwt(string $tokenString): UnencryptedToken
    {
        $parser = new Parser(new JoseEncoder());
        $token = $parser->parse($tokenString);

        if (! $token instanceof UnencryptedToken) {
            throw new Exception(__('messages.error_invalid', ['data' => 'token']), 400);
        }

        $algorithm = new Sha256();
        $signingKey = InMemory::plainText(env('JWT_SECRET'));
        $validator = new Validator();
        $signedWith = new SignedWith($algorithm, $signingKey);
        $clock = SystemClock::fromSystemTimezone();
        $validAt = new StrictValidAt($clock);

        if (!$validator->validate($token, $signedWith, $validAt)) {
            throw new Exception(__('messages.error_invalid', ['data' => 'token']), 400);
        }

        return $token;
    }
}