<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use App\Services\Escrow\EscrowService;
use Illuminate\Http\Request;

class EscrowController extends Controller
{
    public function buyerRelease(Request $request, SubOrder $subOrder, EscrowService $escrow)
    {
        $subOrder->buyer_marked_released = true;
        $subOrder->released_cents = $subOrder->subtotal_cents;
        $subOrder->save();

        $transfer = $escrow->releaseToVendor($subOrder, $request->user());

        return response()->json(['released' => true, 'transfer' => $transfer]);
    }
}
