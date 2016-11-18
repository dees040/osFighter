<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Car\StoreRequest;
use App\Http\Requests\Admin\Car\UpdateRequest;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::all();

        return view('admin.car.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.car.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $car = $request->persist();

        return redirect()->route('cars.show', $car)
            ->with('m_success', 'The car has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return view('admin.car.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        return view('admin.car.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Car $car)
    {
        $request->persist();

        return redirect()->route('cars.show', $car)
            ->with('m_success', 'Car has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $car->delete();

        return redirect()->route('cars.index')
            ->with('m_success', 'The ' . $car->name . ' has been deleted.');
    }
}
