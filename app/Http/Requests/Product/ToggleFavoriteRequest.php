<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ToggleFavoriteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'favoritable_type' => ['required', 'in:product,vendor'],
            'favoritable_id' => ['required', 'integer'],
        ];
    }
}
