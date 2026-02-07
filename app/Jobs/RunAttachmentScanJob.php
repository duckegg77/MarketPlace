<?php

namespace App\Jobs;

use App\Models\MessageAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunAttachmentScanJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $attachmentId)
    {
    }

    public function handle(): void
    {
        MessageAttachment::query()->find($this->attachmentId);
        // Hook for ClamAV/SaaS scanning provider.
    }
}
