<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rank\StoreRequest;

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

        $url = route('config.index') . '#tab-ranks';

        return redirect($url)
            ->with('m_success', 'Ranks are updated with success.');
    }
}
