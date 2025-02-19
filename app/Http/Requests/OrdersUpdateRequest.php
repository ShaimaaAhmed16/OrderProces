<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdersUpdateRequest extends FormRequest
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
            'payment_method'  => 'required|string',
            'payment_status'  => 'required|string|in:pending,paid,failed',
            'address'         => 'required|string|max:255',
            'phone'          => 'required',
            'email'          => 'required|email|max:255',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'country'        => 'required|string|max:100',
            'shipping_price' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:order_details,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }
}
