<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Location\StoreRequest;

class LocationController extends Controller
{
    /**
     * Update the locations.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $request->persist();

        return redirect()->route('config.index')
            ->with('m_success', 'Locations updated.');
    }
}
