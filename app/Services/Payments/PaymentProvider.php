<?php

namespace App\Services\Payments;

interface PaymentProvider
{
    public function authorize(int $amount, string $currency, array $metadata = []): array;
    public function capture(string $authorizationId): array;
    public function refund(string $paymentId, int $amount): array;
    public function transferToVendor(string $vendorAccountId, int $amount, array $metadata = []): array;
}
