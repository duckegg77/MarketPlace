<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\StoreReportRequest;
use App\Jobs\RunAttachmentScanJob;
use App\Models\ConversationThread;
use App\Models\Message;
use App\Models\Report;
use App\Models\SubOrder;
use App\Services\Messaging\BlockGuardService;
use App\Services\Messaging\MessageModerationService;
use App\Services\Security\PgpMessageEncryptionService;

class MessageController extends Controller
{
    public function store(
        StoreMessageRequest $request,
        int $subOrderId,
        MessageModerationService $moderation,
        BlockGuardService $blocks,
        PgpMessageEncryptionService $pgp
    ) {
        $subOrder = SubOrder::query()->findOrFail($subOrderId);
        $counterpartyId = $request->user()->id === $subOrder->vendor_id ? $subOrder->order->buyer_id : $subOrder->vendor_id;

        if ($blocks->blockedEitherWay($request->user()->id, $counterpartyId)) {
            return response()->json(['message' => 'Messaging unavailable due to user block.'], 403);
        }

        $recipient = $request->user()->id === $subOrder->order->buyer_id
            ? $subOrder->vendor
            : $subOrder->order->buyer;

        $thread = ConversationThread::query()->firstOrCreate(['sub_order_id' => $subOrderId]);
        $body = $request->validated('body', '');
        $flagged = $moderation->shouldFlag($body);

        $message = Message::query()->create([
            'thread_id' => $thread->id,
            'sender_id' => $request->user()->id,
            'recipient_id' => $recipient?->id,
            'body' => null,
            'encrypted_body' => $pgp->encryptForRecipient($body, $recipient),
            'encryption_scheme' => 'pgp',
            'message_type' => $request->validated('message_type'),
            'is_flagged' => $flagged,
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('private/messages', 'local');
            $attachment = $message->attachments()->create([
                'disk' => 'local',
                'path' => $path,
                'original_name' => $request->file('attachment')->getClientOriginalName(),
                'mime_type' => $request->file('attachment')->getClientMimeType(),
                'size_bytes' => $request->file('attachment')->getSize(),
            ]);
            RunAttachmentScanJob::dispatch($attachment->id);
        }

        return response()->json($message, 201);
    }

    public function report(StoreReportRequest $request)
    {
        $report = Report::query()->create([
            'reporter_id' => $request->user()->id,
            'reportable_type' => $request->validated('reportable_type'),
            'reportable_id' => $request->validated('reportable_id'),
            'reason_code' => $request->validated('reason_code'),
            'details' => $request->validated('details'),
        ]);

        return response()->json($report, 201);
    }
}
