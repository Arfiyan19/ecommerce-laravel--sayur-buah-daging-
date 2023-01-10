<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topup = User::all();
        // $topup = $users;
        // return dd($topup);
        return view('backend.topup.index')->with('topup', $topup);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topup = User::findOrFail($id);
        // return dd($topup);
        return view('backend.topup.edit')->with('topup', $topup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $topup = User::findOrFail($id);
        // $validate = $request->validate([
        //     // 'saldo' => 'required|numeric',
        //     // 'saldo_pending' => 'required|numeric',
        //     // 'bukti_tf' => 'string|required',
        //     // 'saldo_pending_status'=>'required|in:active,inactive',
        //     // 'saldo_pending_status' => 'required|string',
        //     // 's' => 'required|string',
        // ]);
        $topup->saldo = ($topup->saldo + $topup->saldo_pending);
        $topup->saldo_pending_status = $request->saldo_pending_status;
        $topup->saldo_pending = '0';
        $topup->save();

        request()->session()->flash('success', 'Topup  successfully Update');
        return redirect()->route('saldo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
