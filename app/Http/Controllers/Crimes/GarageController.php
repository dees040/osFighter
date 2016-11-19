<?php

namespace App\Http\Controllers\Crimes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crimes\Garage\StoreRequest;

class GarageController extends Controller
{
    /**
     * Show the garage page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cars = currentUser()->cars()->paginate(10);

        return view('crimes.garage', compact('cars'));
    }

    /**
     * Perform a garage action (repair/sell).
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreRequest $request)
    {
        return $request->persist();
    }
}
