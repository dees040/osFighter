<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Group;
use App\Models\Route;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Route\UpdateRequest;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = Route::with('menu')->get();

        return view('admin.route.index', compact('routes'));
    }

    /**
     * Display the specified resource.
     *
     * @param Route $route
     * @return \Illuminate\Http\Response
     * @ param int $id
     */
    public function show(Route $route)
    {
        $route->load('menu');

        return view('admin.route.show', compact('route'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Route $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        $menus = Menu::all();
        $groups = Group::all();

        return view('admin.route.edit', compact('route', 'menus', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param Route $route
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Route $route)
    {
        $request->persist();

        return redirect()->route('routes.show', $route)
            ->with('m_success', $route->title . ' page updated with success.');
    }
}
