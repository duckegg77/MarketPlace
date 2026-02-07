<?php

use App\Models\CurrencyRate;
use App\Services\Payments\CurrencyDisplayService;

it('converts usd cents to usd gbp eur and xmr display values', function () {
    CurrencyRate::query()->create(['base_currency' => 'USD', 'quote_currency' => 'GBP', 'rate' => 0.8, 'as_of' => now()]);
    CurrencyRate::query()->create(['base_currency' => 'USD', 'quote_currency' => 'EUR', 'rate' => 0.9, 'as_of' => now()]);
    CurrencyRate::query()->create(['base_currency' => 'USD', 'quote_currency' => 'XMR', 'rate' => 0.005, 'as_of' => now()]);

    $display = app(CurrencyDisplayService::class)->convertFromUsdCents(10000);

    expect($display)->toHaveKeys(['USD', 'GBP', 'EUR', 'XMR']);
});
