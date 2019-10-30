<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'avatar' => 'image|mimes:jpg,jpeg,png,gif,svg|max:1024|dimensions:width=128,height=128',
            // |の代わりに配列でも指定可
            'locale' => [
                'required',
                // Rule::in methodで指定したリストの中の値に含まれていることをバリデート
                Rule::in(array_keys(User::LOCALES)),
            ],
        ];
    }
}
