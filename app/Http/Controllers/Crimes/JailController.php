<?php

namespace App\Http\Controllers\Crimes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crimes\JailFreeStoreRequest;

class JailController extends Controller
{
    /**
     * The jail index page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = game()->usersInJail();

        return view('crimes.jail', compact('users'));
    }

    /**
     * Buy a user free from the jail.
     *
     * @param  JailFreeStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JailFreeStoreRequest $request)
    {
        return $request->persist();
    }
}
