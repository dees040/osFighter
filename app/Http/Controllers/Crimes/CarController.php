<?php

namespace App\Http\Controllers\Crimes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Crimes\Car\StoreRequest;

class CarController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $request->persist();
    }
}
