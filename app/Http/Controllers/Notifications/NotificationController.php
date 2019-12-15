<?php

namespace App\Http\Controllers\Notifications;

use App\Notification;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
            'phone' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'name' => 'required',
            'url' => 'required',
        ]);

        $validatedData['phone'] = str_replace(' ', '', $validatedData['phone']);

        $notifications = DB::table('notifications')
        ->insert(['user_id' => $user->id, 'phone_number' => $validatedData['phone'], 'date' => $validatedData['date'], 'time' => $validatedData['time'], 'restaurant_name' => $validatedData['name'], 'restaurant_url' => $validatedData['url']]);

        $this->sendSms($validatedData, $user);
        $this->insertReminder($validatedData, $user);
        return response()->json($notifications);
    }
    private function sendSms($data, $user)
    {
        $account_sid = getenv("TWILIO_ACCOUNT_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_PHONE_NUMBER");
        $client = new Client($account_sid, $auth_token);

        $message = "You have been invited to ". $data['name'] ." by ". $user->name ." on ".$data['date']." at ". $data['time'] .". \n\r More info: ". $data['url'] ."\n\r Reply to the number ". $twilio_number ." with 'Accept' or 'Decline'";

        $client->messages->create($data['phone'],
                ['from' => $twilio_number, 'body' => $message] );
    }
    public function insertReminder($data, $user){

        $timezoneoffset = Carbon::parse("{$data['date']} {$data['time']}");

        $reminders = DB::table('reminders')
        ->insert(['mobile_no' => $data['phone'], 'timezoneoffset' => $timezoneoffset, 'message' => "". $data['name'] ." meeting with ". $user->name . " at ". $data['time'] ."", 'created_at' => date("Y-m-d H:i:s")]);

        return response()->json($reminders);
    }
    public function getnotifications(Request $request)
    {
        $user = $request->user();

        $notifications = DB::table('notifications')
        ->select(['phone_number', 'reply', 'date', 'time', 'restaurant_name'])
        ->where('user_id', $user->id)
        ->orderBy('id','desc')
        ->limit(5)
        ->get();

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