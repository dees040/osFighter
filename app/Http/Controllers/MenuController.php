<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\Menu\UpdateRequest;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::with('pages')->get();

        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Display the specified resource.
     *
     * @param Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        $menu->load('pages');

        return view('admin.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Menu $menu)
    {
        $request->persist();

        return redirect()->route('menus.show', $menu);
    }
}
