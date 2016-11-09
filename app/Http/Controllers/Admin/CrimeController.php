<?php

namespace App\Http\Controllers\Admin;

use App\Models\Crime;
use App\Http\Controllers\Controller;
use App\Http\Requests\Crime\StoreRequest;
use App\Http\Requests\Crime\UpdateRequest;

class CrimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crimes = Crime::all()->sortBy('chance');

        return view('admin.crime.index', compact('crimes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.crime.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $crime = $request->persist();

        return redirect()->route('crimes.show', $crime);
    }

    /**
     * Display the specified resource.
     *
     * @param Crime $crime
     * @return \Illuminate\Http\Response
     */
    public function show(Crime $crime)
    {
        return view('admin.crime.show', compact('crime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Crime $crime
     * @return \Illuminate\Http\Response
     */
    public function edit(Crime $crime)
    {
        return view('admin.crime.edit', compact('crime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param Crime $crime
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Crime $crime)
    {
        $request->persist();

        return redirect()->route('crimes.show', $crime);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Crime $crime
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crime $crime)
    {
        $crime->delete();

        return redirect()->route('crimes.index');
    }
}
