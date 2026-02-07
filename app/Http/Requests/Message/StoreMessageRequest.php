<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string', 'max:5000'],
            'message_type' => ['required', 'in:text,image,file'],
            'attachment' => ['nullable', 'file', 'max:8192'],
        ];
    }
}
