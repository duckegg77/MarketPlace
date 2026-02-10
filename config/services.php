<?php

return [
    'fx' => [
        'fiat_source' => env('FX_FIAT_SOURCE', 'https://open.er-api.com/v6/latest/USD'),
        'xmr_source' => env('FX_XMR_SOURCE', 'https://api.coingecko.com/api/v3/simple/price?ids=monero&vs_currencies=usd'),
    ],
];
