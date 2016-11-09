<?php

namespace App\Http\Controllers\Crimes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crimes\CrimeStoreRequest;
use App\Models\Crime;

class CrimeController extends Controller
{
    /**
     * Show the form for creating a crime.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $crimes = Crime::all()->sortBy('chance');

        return view('crimes.crime', compact('crimes'));
    }

    /**
     * Store a newly created crime.
     *
     * @param  CrimeStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrimeStoreRequest $request)
    {
        return $request->persist();
    }
}
