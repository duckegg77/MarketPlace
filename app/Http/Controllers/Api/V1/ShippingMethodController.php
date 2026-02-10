<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            ShippingMethod::query()->where('vendor_id', $request->integer('vendor_id'))->where('is_active', true)->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'estimated_days' => ['nullable', 'integer', 'min:1'],
        ]);

        $method = ShippingMethod::query()->create($data + ['vendor_id' => $request->user()->id]);

        return response()->json($method, 201);
    }
}
