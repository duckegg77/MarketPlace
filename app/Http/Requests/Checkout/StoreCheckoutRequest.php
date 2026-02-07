<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'shipping_address' => ['required', 'array'],
            'coupon_code' => ['nullable', 'string'],
            'currency' => ['nullable', 'in:USD,GBP,EUR,XMR'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.vendor_id' => ['required', 'integer'],
            'items.*.unit_price_cents' => ['required', 'integer', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.shipping_method_id' => ['nullable', 'integer'],
        ];
    }
}
