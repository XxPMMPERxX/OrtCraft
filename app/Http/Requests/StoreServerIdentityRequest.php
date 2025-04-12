<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServerIdentityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address' => [
                'required',
                'string',
                'regex:/^(?:(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}|(?:25[0-5]|2[0-4]\d|1\d\d|\d{1,2})\.(?:25[0-5]|2[0-4]\d|1\d\d|\d{1,2})\.(?:25[0-5]|2[0-4]\d|1\d\d|\d{1,2})\.(?:25[0-5]|2[0-4]\d|1\d\d|\d{1,2}))$/'
            ],
            'je_port' => [
                'required_without:be_port',
                'integer',
                'min:1',
                'max:65535',
            ],
            'be_port' => [
                'required_without:je_port',
                'min:1',
                'max:65535',
            ],
        ];
    }
}
