<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'name' =>'required|string',
            'description' =>'required|string',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price'=>'required|numeric',
            'discount_price'=>'nullable|numeric',
            'category_id'=>'required|exists:categories,id',
        ];
    }
}
