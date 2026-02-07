<?php

use App\Models\LedgerAccount;
use App\Services\Escrow\LedgerService;

it('posts double entry ledger record', function () {
    LedgerAccount::query()->create(['code' => 'buyer_receivable', 'name' => 'Buyer', 'type' => 'asset']);
    LedgerAccount::query()->create(['code' => 'platform_escrow', 'name' => 'Escrow', 'type' => 'liability']);

    $entry = app(LedgerService::class)->post('buyer_receivable', 'platform_escrow', 1000, ['reference' => 'test']);

    expect($entry->amount_cents)->toBe(1000)
        ->and($entry->reference)->toBe('test');
});
