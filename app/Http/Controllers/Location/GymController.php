<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GymController extends Controller
{
    public function create()
    {
        return view('location.gym');
    }
}
