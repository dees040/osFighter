<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Settings\UpdateRequest;

class SettingsController extends Controller
{
    /**
     * Show the edit form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('user.settings.edit');
    }

    /**
     * Update the user account.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $request->persist();

        $url = route('settings.update') . '#tab-text';

        return redirect($url)
            ->with('m_success', 'Your settings have been updated.');
    }
}
