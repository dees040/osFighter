<?php

namespace App\Http\Controllers\Location;

use App\Http\Requests\Location\Airport\StoreRequest;
use App\Models\Location;
use App\Http\Controllers\Controller;

class AirportController extends Controller
{
    /**
     * Show the airport.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $locations = Location::all();

        return view('location.airport', compact('locations'));
    }

    /**
     * Go fly to another location.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $request->persist();

        return redirect()->route('airport.index')
            ->with('m_success', 'You \'re flying!');
    }
}
