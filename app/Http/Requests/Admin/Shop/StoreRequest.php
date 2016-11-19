<?php

namespace App\Http\Requests\Admin\Shop;

use App\Models\ShopItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name'           => 'required|min:2|max:100',
            'image'          => 'required|image|max:5000',
            'price'          => 'required|numeric|min:2',
            'power'          => 'required|numeric|min:2',
            'min_strength_points' => 'required|numeric|min:0',
            'max_amount'     => 'required|numeric|min:0',
        ];
    }

    /**
     * Persist the request.
     *
     * @return ShopItem
     */
    public function persist()
    {
        $item = ShopItem::create($this->only('name', 'price', 'power', 'min_strength_points', 'max_amount'));

        $item->storeImage($this->file('image'));

        return $item;
    }
}
