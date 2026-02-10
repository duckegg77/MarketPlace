<?php

namespace App\Services\Payments;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Http;
use Throwable;

class LiveRateSyncService
{
    public function sync(): array
    {
        $updates = [];

        try {
            $fiat = Http::timeout(8)->get((string) config('services.fx.fiat_source', env('FX_FIAT_SOURCE', 'https://open.er-api.com/v6/latest/USD')))->json();
            $gbp = (float) data_get($fiat, 'rates.GBP');
            $eur = (float) data_get($fiat, 'rates.EUR');

            if ($gbp > 0) {
                $updates['GBP'] = $gbp;
            }

            if ($eur > 0) {
                $updates['EUR'] = $eur;
            }
        } catch (Throwable) {
            // Keep existing rates on API failure.
        }

        try {
            $xmrQuote = Http::timeout(8)
                ->get((string) config('services.fx.xmr_source', env('FX_XMR_SOURCE', 'https://api.coingecko.com/api/v3/simple/price?ids=monero&vs_currencies=usd')))
                ->json();
            $usdPerXmr = (float) data_get($xmrQuote, 'monero.usd');

            if ($usdPerXmr > 0) {
                $updates['XMR'] = round(1 / $usdPerXmr, 12);
            }
        } catch (Throwable) {
            // Keep existing rates on API failure.
        }

        $updates['USD'] = 1.0;

        foreach ($updates as $quote => $rate) {
            CurrencyRate::query()->updateOrCreate(
                ['base_currency' => 'USD', 'quote_currency' => $quote],
                ['rate' => $rate, 'as_of' => now()]
            );
        }

        return $updates;
    }
}
