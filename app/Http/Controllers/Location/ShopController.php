<?php

namespace App\Http\Controllers\Location;

use App\Models\ShopItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Location\Shop\StoreRequest;

class ShopController extends Controller
{
    /**
     * Shop the shop.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $items = ShopItem::all()->sortBy('power');

        return view('location.shop', compact('items'));
    }

    /**
     * Buy items from shop.
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $request->persist();
    }
}
