<?php

namespace App\Http\Controllers\Extra;

use App\Models\ShoutBox;
use App\Http\Controllers\Controller;
use App\Http\Requests\Extra\ShoutBox\StoreRequest;

class ShoutBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = ShoutBox::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('extra.shoutbox', compact('messages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $request->persist();

        return redirect()->route('shoutbox.index')
            ->with('m_success', 'Messages posted in shout box with success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ShoutBox  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShoutBox $message)
    {
        $message->delete();

        return redirect()->route('shoutbox.index')
            ->with('m_success', 'Message deleted.');
    }
}
