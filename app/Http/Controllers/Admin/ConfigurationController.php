<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use App\Models\Rank;
use App\Models\Crime;
use App\Models\Group;
use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Configuration\UpdateRequest;

class ConfigurationController extends Controller
{
    /**
     * Show the configs.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $groups = Group::all();
        $ranks = Rank::all()->sortBy('level');
        $crimes = Crime::all()->sortBy('chance');
        $cars = Car::all()->sortBy('price');
        $locations = Location::all();

        return view('admin.config.index', compact('groups', 'ranks', 'crimes', 'cars', 'locations'));
    }

    /**
     * Update the configuration.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $request->persist();

        return redirect()->route('config.index')
            ->with('m_success', 'Configuration updated with success.');
    }
}
