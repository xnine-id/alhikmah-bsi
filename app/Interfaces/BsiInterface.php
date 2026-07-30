<?php

namespace App\Interfaces;

interface BsiInterface
{
    public function authenticate(string $signature, string $clientKey, string $timestamp): array;
    public function inquiry(array $headers, array $payload): array;
    public function payment(array $headers, array $payload): array;
}
