<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;

class UserController extends Controller
{
    /**
     * Show an user.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $user = user($user);

        return view('user.show', compact('user'));
    }

    /**
     * Show the edit form.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $user->load('info');

        return view('user.show', compact('user'));
    }

    /**
     * Update the user account.
     *
     * @param UpdateRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
        $request->persist();

        return redirect()->route('users.show', $user)
            ->with('m_success', 'Your info has been updated with success.');
    }
}
