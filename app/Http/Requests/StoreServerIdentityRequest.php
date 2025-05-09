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
            'label' => [
                'string',
                'nullable',
                'max:40',
            ],
            'address' => [
                'required',
                'string',
            ],
            'je_port' => [
                'required_without:be_port',
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],
            'be_port' => [
                'required_without:je_port',
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],
        ];
    }
}
