<?php

namespace App\Http\Controllers\Reply;

use App\Reply;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReplyController extends Controller
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
        return view('createreply');
    }
    public function commandHandler(Request $request)
    {
        $from = $request->input("From");
        $body = strtolower($request->input("Body"));
        $status;
        if ($body == 'accept') {
            $status = 'Accepted';
            DB::table('notifications')
            ->where('phone_number', $from)
            ->orderBy('id','desc')
            ->take(1)
            ->update(['reply' => $status]);
            //$this->modifyTable();
        }
        elseif($body == 'decline'){
            $status = 'Declined';
            DB::table('notifications')
            ->where('phone_number', $from)
            ->orderBy('id','desc')
            ->take(1)
            ->update(['reply' => $status]);
        }
        $this->sendMessage($from, $status);
        return "message received".$body." ". $from ." " . $request . "";
    }
    private function sendMessage($to, $status)
    {
        $account_sid = getenv("TWILIO_ACCOUNT_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_PHONE_NUMBER");
        $client = new Client($account_sid, $auth_token);

        $message = "Confirmation: You have ". $status ." the invitation";
        $client->messages->create($to,
                ['from' => $twilio_number, 'body' => $message] );
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