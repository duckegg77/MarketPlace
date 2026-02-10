<?php

namespace App\Services\Payments;

class StripePaymentProvider implements PaymentProvider
{
    public function authorize(int $amount, string $currency, array $metadata = []): array
    {
        return ['authorization_id' => 'auth_'.uniqid(), 'amount' => $amount, 'currency' => $currency, 'metadata' => $metadata];
    }

    public function capture(string $authorizationId): array
    {
        return ['payment_id' => 'pay_'.uniqid(), 'authorization_id' => $authorizationId, 'status' => 'captured'];
    }

    public function refund(string $paymentId, int $amount): array
    {
        return ['refund_id' => 'refund_'.uniqid(), 'payment_id' => $paymentId, 'amount' => $amount, 'status' => 'succeeded'];
    }

    public function transferToVendor(string $vendorAccountId, int $amount, array $metadata = []): array
    {
        return ['transfer_id' => 'tr_'.uniqid(), 'vendor_account_id' => $vendorAccountId, 'amount' => $amount, 'metadata' => $metadata];
    }
}
