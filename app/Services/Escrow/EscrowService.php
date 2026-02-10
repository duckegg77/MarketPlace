<?php

namespace App\Services\Escrow;

use App\Models\AuditLog;
use App\Models\Dispute;
use App\Models\SubOrder;
use App\Models\User;
use App\Services\Payments\PaymentProvider;

class EscrowService
{
    public function __construct(private readonly LedgerService $ledger, private readonly PaymentProvider $provider)
    {
    }

    public function captureToEscrow(int $orderId, int $amount, string $paymentReference): void
    {
        $this->ledger->post('buyer_receivable', 'platform_escrow', $amount, [
            'order_id' => $orderId,
            'reference' => $paymentReference,
        ]);
    }

    public function releaseToVendor(SubOrder $subOrder, User $actor): array
    {
        $entry = $this->ledger->post('platform_escrow', 'vendor_payable', $subOrder->subtotal_cents, [
            'order_id' => $subOrder->order_id,
            'sub_order_id' => $subOrder->id,
            'reference' => 'release:'.$subOrder->id,
        ]);

        $transfer = $this->provider->transferToVendor('vendor_'.$subOrder->vendor_id, $subOrder->subtotal_cents, [
            'sub_order_id' => $subOrder->id,
        ]);

        AuditLog::query()->create([
            'actor_id' => $actor->id,
            'action' => 'escrow.released',
            'auditable_type' => SubOrder::class,
            'auditable_id' => $subOrder->id,
            'after' => ['ledger_entry_id' => $entry->id, 'transfer' => $transfer],
        ]);

        return $transfer;
    }

    public function refundForDispute(Dispute $dispute, User $actor): void
    {
        $subOrder = SubOrder::query()->findOrFail($dispute->sub_order_id);

        $this->ledger->post('platform_escrow', 'buyer_receivable', $subOrder->subtotal_cents, [
            'order_id' => $subOrder->order_id,
            'sub_order_id' => $subOrder->id,
            'dispute_id' => $dispute->id,
            'reference' => 'refund:'.$dispute->id,
        ]);

        AuditLog::query()->create([
            'actor_id' => $actor->id,
            'action' => 'escrow.refunded',
            'auditable_type' => Dispute::class,
            'auditable_id' => $dispute->id,
            'after' => ['sub_order_id' => $subOrder->id],
        ]);
    }
}
