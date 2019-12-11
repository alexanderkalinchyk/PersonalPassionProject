<?php

namespace App\Http\Controllers\Notification;

use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createnotification');
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
    public function sendnotification(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'phone_number' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'restaurant_name' => 'required',
            'restaurant_url' => 'required',
        ]);

        $notifications = DB::table('notifications')
        ->insert(['user_id' => $user->id, 'phone_number' => $validatedData['phone_number'], 'date' => $validatedData['date'], 'time' => $validatedData['time'], 'restaurant_name' => $validatedData['restaurant_name'], 'restaurant_url' => $validatedData['restaurant_url']]);

        return response()->json($notifications);


    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
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
        //
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
        //
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