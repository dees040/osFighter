<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\Bank\StoreRequest;

class BankController extends Controller
{
    /**
     * Show the form for managing the user it's bank account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $logs = currentUser()->bankLogs()->orderBy('created_at', 'desc')->paginate(15);

        return view('location.bank', compact('logs'));
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        return $request->persist();
    }
}
