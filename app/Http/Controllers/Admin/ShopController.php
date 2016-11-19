<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shop\StoreRequest;
use App\Http\Requests\Admin\Shop\UpdateRequest;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = ShopItem::orderBy('power')->get();

        return view('admin.shop.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $item = $request->persist();

        return redirect()->route('shop.show', $item)
            ->with('m_success', $item->name . ' item has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  ShopItem  $item
     * @return \Illuminate\Http\Response
     */
    public function show(ShopItem $item)
    {
        return view('admin.shop.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ShopItem  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopItem $item)
    {
        return view('admin.shop.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  ShopItem  $item
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ShopItem $item)
    {
        $request->persist();

        return redirect()->route('shop.show', $item)
            ->with('m_success', $item->name . ' item has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ShopItem  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopItem $item)
    {
        $item->delete();

        return redirect()->route('shop.index')
            ->with('m_success', $item->name . ' item has been destroyed.');
    }
}
