<?php

namespace App\Http\Controllers\Location;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

class RLDCashOutController extends Controller
{
    /**
     * Get cash from the Red Light District.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $cash = user()->getCashAmountFromHoes();

        if ($cash > 0) {
            user()->add('cash', $cash)
                ->updateTime('pimped_cash', Carbon::now());
        }

        return redirect()->route('rld.index')
            ->with('m_success', 'You earned ' . money($cash) . ' in cash.');
    }
}
