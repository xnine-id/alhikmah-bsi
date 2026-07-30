<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\RateLimiter;

trait Throttable
{
    private function throttle(string $key, $maxAttempts = 5, $decaySeconds = 60)
    {
        $fullKey = $key . ":" . request()->ip();

        if (RateLimiter::tooManyAttempts($fullKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($fullKey);

            throw new Exception(__('messages.error_throttled', ['seconds' => $seconds]), 429);
        }

        RateLimiter::hit($key, $decaySeconds);
    }

    private function clearThrottle(string $key) {
        $fullKey = $key . ":" . request()->ip();
        RateLimiter::clear($fullKey);
    }
}
