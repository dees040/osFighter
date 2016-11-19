<?php

namespace App\Http\Requests\Location\Shop;

use App\Models\ShopItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'item'   => [
                'required',
                Rule::exists('shop_items', 'id'),
            ],
            'amount' => [
                'strength_points:' . $this->item,
                'shop_item_amount:' . $this->item,
                'has_cash:' . ShopItem::find($this->item)->price * $this->amount,
            ],
        ];
    }

    /**
     * Persist the request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function persist()
    {
        $item = ShopItem::find($this->item);

        $userItem = user()->getShopItem($item);

        if (is_null($userItem)) {
            currentUser()->shopItems()->attach($item, ['amount' => $this->amount]);
        } else {
            currentUser()
                ->shopItems()
                ->updateExistingPivot($item->id, ['amount' => $userItem->pivot->amount + $this->amount]);
        }

        user()
            ->add('power', $item->power * $this->amount)
            ->take('cash', $item->price * $this->amount);

        return back()
            ->with('m_success', "You successfully bought the $item->name $this->amount times.");
    }
}
