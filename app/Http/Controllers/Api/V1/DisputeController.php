<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dispute\StoreDisputeRequest;
use App\Models\AuditLog;
use App\Models\Dispute;
use App\Services\Escrow\EscrowService;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function store(StoreDisputeRequest $request)
    {
        $dispute = Dispute::query()->create([
            'sub_order_id' => $request->validated('sub_order_id'),
            'buyer_id' => $request->user()->id,
            'reason' => $request->validated('reason'),
            'status' => 'opened',
            'opened_at' => now(),
        ]);

        AuditLog::query()->create([
            'actor_id' => $request->user()->id,
            'action' => 'dispute.opened',
            'auditable_type' => Dispute::class,
            'auditable_id' => $dispute->id,
            'after' => $dispute->toArray(),
        ]);

        return response()->json($dispute, 201);
    }

    public function resolve(Request $request, Dispute $dispute, EscrowService $escrow)
    {
        $data = $request->validate([
            'resolution' => ['required', 'in:release,refund,partial_refund'],
        ]);

        if ($data['resolution'] === 'refund') {
            $escrow->refundForDispute($dispute, $request->user());
        }

        $dispute->status = 'resolved';
        $dispute->resolved_at = now();
        $dispute->save();

        AuditLog::query()->create([
            'actor_id' => $request->user()->id,
            'action' => 'dispute.resolved',
            'auditable_type' => Dispute::class,
            'auditable_id' => $dispute->id,
            'after' => ['resolution' => $data['resolution']],
        ]);

        return response()->json($dispute);
    }
}
