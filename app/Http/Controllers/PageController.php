<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use App\Http\Requests\Page\UpdateRequest;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::with('menu')->get();

        return view('admin.page.index', compact('pages'));
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     * @return \Illuminate\Http\Response
     * @ param int $id
     */
    public function show(Page $page)
    {
        $page->load('menu');

        return view('admin.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $menus = Menu::all();

        return view('admin.page.edit', compact('page', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Page $page)
    {
        $request->persist();

        return redirect()->route('pages.show', $page);
    }
}
