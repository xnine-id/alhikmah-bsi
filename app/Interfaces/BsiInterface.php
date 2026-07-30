<?php

namespace App\Interfaces;

interface BsiInterface
{
    public function authenticate(string $signature, string $clientKey, string $timestamp): array;
    public function inquiry(array $headers, array $payload, string $rawBody = ''): array;
    public function payment(array $headers, array $payload, string $rawBody = ''): array;
}
