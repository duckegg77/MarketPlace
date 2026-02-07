<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'reportable_type' => ['required', 'in:message,user,product,vendor'],
            'reportable_id' => ['required', 'integer'],
            'reason_code' => ['required', 'string', 'max:100'],
            'details' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
