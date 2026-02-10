<?php

use App\Services\Payments\LiveRateSyncService;

it('syncs live fiat and xmr rates', function () {
    $this->markTestIncomplete('Requires HTTP fakes and full Laravel test runtime in this environment.');

    app(LiveRateSyncService::class)->sync();
});
