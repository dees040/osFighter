<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\Admin\StoreRequest;
use App\Http\Requests\Group\Admin\UpdateRequest;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::with('users', 'child', 'parent')->get();

        return view('admin.group.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();

        return view('admin.group.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $group = $request->persist();

        return redirect()->route('groups.show', $group)
            ->with('m_success', $group->name . ' created with success.');
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return view('admin.group.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $groups = Group::all();

        return view('admin.group.edit', compact('group', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Group $group)
    {
        $request->persist();

        return redirect()->route('groups.show', $group)
            ->with('m_success', $group->name . ' updated with success.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $name = $group->name;

        $group->delete();

        return redirect()->route('groups.index')
            ->with('m_success', $name . ' destroyed with success.');
    }
}
