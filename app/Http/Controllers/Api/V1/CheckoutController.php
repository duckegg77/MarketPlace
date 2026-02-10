<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StoreCheckoutRequest;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\SubOrder;
use App\Services\Escrow\EscrowService;
use App\Services\Payments\PaymentProvider;

class CheckoutController extends Controller
{
    public function __invoke(StoreCheckoutRequest $request, PaymentProvider $provider, EscrowService $escrow)
    {
        $items = $request->validated('items', []);
        $currency = $request->validated('currency', 'USD');

        $grouped = collect($items)->groupBy('vendor_id');
        $amount = (int) $grouped->flatten(1)->sum(fn ($i) => $i['unit_price_cents'] * $i['quantity']);

        $auth = $provider->authorize($amount, $currency, ['buyer_id' => $request->user()->id]);
        $capture = $provider->capture($auth['authorization_id']);

        $order = Order::query()->create([
            'buyer_id' => $request->user()->id,
            'status' => 'paid',
            'total_cents' => $amount,
            'currency' => $currency,
            'payment_reference' => $capture['payment_id'],
            'shipping_address_encrypted' => $request->validated('shipping_address'),
        ]);

        $subOrders = [];
        foreach ($grouped as $vendorId => $vendorItems) {
            $shippingMethod = ShippingMethod::query()->find($vendorItems->first()['shipping_method_id'] ?? null);
            $subtotal = (int) $vendorItems->sum(fn ($i) => $i['unit_price_cents'] * $i['quantity']) + (int) ($shippingMethod->price_cents ?? 0);

            $subOrders[] = SubOrder::query()->create([
                'order_id' => $order->id,
                'vendor_id' => $vendorId,
                'subtotal_cents' => $subtotal,
                'selected_shipping_method' => $shippingMethod?->name,
                'shipping_cents' => $shippingMethod->price_cents ?? 0,
                'release_eligible_at' => now()->addDays(7),
            ]);
        }

        $escrow->captureToEscrow($order->id, $amount, $capture['payment_id']);

        return response()->json(['order' => $order, 'sub_orders' => $subOrders], 201);
    }
}
