<?php

namespace App\Http\Controllers\Family;

use App\Models\Family;
use App\Http\Controllers\Controller;
use App\Http\Requests\Family\StoreRequest;
use App\Http\Requests\Family\UpdateRequest;

class FamilyController extends Controller
{
    /**
     * FamilyController constructor.
     */
    public function __construct()
    {
        $this->middleware('family_free')->only('create', 'store');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $families = Family::orderBy('power', 'desc')->paginate(15);

        return view('family.index', compact('families'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('family.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $family = $request->persist();

        return redirect()->route('families.show', $family)
            ->with('m_success', 'All rise for ' . $family->name. '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  Family $family
     * @return \Illuminate\Http\Response
     */
    public function show(Family $family)
    {
        $family->load('creator', 'members');

        return view('family.show', compact('family'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Family $family
     * @return \Illuminate\Http\Response
     */
    public function edit(Family $family)
    {
        return view('family.edit', compact('family'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Family $family
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Family $family)
    {
        $request->persist();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Family $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(Family $family)
    {
        //
    }
}
