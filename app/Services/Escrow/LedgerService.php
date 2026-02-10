<?php

namespace App\Services\Escrow;

use App\Models\LedgerAccount;
use App\Models\LedgerEntry;

class LedgerService
{
    public function post(string $debitCode, string $creditCode, int $amount, array $context = []): LedgerEntry
    {
        $debit = LedgerAccount::query()->firstWhere('code', $debitCode);
        $credit = LedgerAccount::query()->firstWhere('code', $creditCode);

        return LedgerEntry::query()->create([
            'debit_account_id' => $debit->id,
            'credit_account_id' => $credit->id,
            'amount_cents' => $amount,
            'currency' => $context['currency'] ?? 'USD',
            'order_id' => $context['order_id'] ?? null,
            'sub_order_id' => $context['sub_order_id'] ?? null,
            'dispute_id' => $context['dispute_id'] ?? null,
            'reference' => $context['reference'] ?? null,
            'metadata' => $context['metadata'] ?? null,
        ]);
    }
}
