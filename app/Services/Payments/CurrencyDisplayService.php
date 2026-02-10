<?php

namespace App\Services\Payments;

use App\Models\CurrencyRate;

class CurrencyDisplayService
{
    public function __construct(private readonly LiveRateSyncService $liveRateSync)
    {
    }

    public function convertFromUsdCents(int $usdCents): array
    {
        $this->refreshIfStale();

        $usd = round($usdCents / 100, 2);

        return [
            'USD' => $usd,
            'GBP' => $this->convert($usd, 'GBP'),
            'EUR' => $this->convert($usd, 'EUR'),
            'XMR' => $this->convert($usd, 'XMR'),
        ];
    }

    private function refreshIfStale(): void
    {
        $latest = CurrencyRate::query()
            ->where('base_currency', 'USD')
            ->latest('as_of')
            ->first();

        if (! $latest || now()->diffInMinutes($latest->as_of) >= 30) {
            $this->liveRateSync->sync();
        }
    }

    private function convert(float $usd, string $quote): float
    {
        $rate = CurrencyRate::query()->where('base_currency', 'USD')->where('quote_currency', $quote)->value('rate') ?? 1;

        return round($usd * (float) $rate, 8);
    }
}
