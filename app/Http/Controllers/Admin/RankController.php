<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rank\Admin\StoreRequest;

class RankController extends Controller
{
    /**
     * Update the ranks.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $request->persist();

        return redirect()->route('config.index')
            ->with('m_success', 'Ranks are updated with success.');
    }
}
