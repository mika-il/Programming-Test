<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'players' => ['required', 'regex:/^[1-9][0-9]*$/']
        ];
    }

    public function messages()
    {
        return [
            'players.regex' => 'Value is invalid.'
        ];
    }
}
