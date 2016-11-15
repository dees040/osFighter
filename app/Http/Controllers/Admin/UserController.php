<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Rank;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('info')->paginate(50);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $ranks = Rank::all();

        return view('admin.user.create', compact('groups', 'ranks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $user = $request->persist();

        return redirect()->route('users.show', $user)
            ->with('m_success', $user->username . ' created with success.');
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('info.rank', 'time', 'group');

        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user->load('info.rank', 'group');
        $groups = Group::all();
        $ranks = Rank::all();

        return view('admin.user.edit', compact('user', 'groups', 'ranks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $request->persist();

        return redirect()->route('users.show', $user)
            ->with('m_success', $user->username . ' updated with success.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->group->id == game()->getAdminGroup()->id) {
            return redirect()->route('users.show', $user)
                ->with('m_danger', 'You can\'t delete an admin.');
        }

        $user->info()->delete();
        $user->time()->delete();
        $user->delete();

        return redirect()->route('users.index')
            ->with('m_success', 'User has been deleted.');
    }
}
