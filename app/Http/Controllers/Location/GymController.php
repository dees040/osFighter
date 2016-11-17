<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\Gym\StoreRequest;

class GymController extends Controller
{
    /**
     * Show the gym.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('location.gym');
    }

    /**
     * Let the user train in the gym.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $strength = $request->persist();

        return redirect()->route('gym.create')
            ->with('m_success', 'You have gained ' . $strength . ' strength points.');
    }
}
