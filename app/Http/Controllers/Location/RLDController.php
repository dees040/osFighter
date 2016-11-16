<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\RLD\StoreRequest;

class RLDController extends Controller
{
    /**
     * Show the RLD form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('location.rld');
    }

    /**
     * Pimp hoes.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $pimped = $request->persist();

        return redirect()->route('rld.index')
            ->with('m_success', 'You pimped ' . $pimped . ' hoes.');
    }
}
