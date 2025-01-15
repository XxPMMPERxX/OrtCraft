<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreServerRequest extends FormRequest
{
    /**
     * マイクラ認証が済んでいる場合のみ、サーバ登録を可能にする
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
            'name' => 'required',
            'description' => [
                'string',
                'nullable',
                'max:1000',
            ],
        ];
    }
}
