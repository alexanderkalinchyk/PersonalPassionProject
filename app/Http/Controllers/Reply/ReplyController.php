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
        if ($body == 'Accept') {
        DB::table('preferences')
        ->where('user_id', 5)
        ->update(['radius' => 6001]);
        $this->sendMessage($from);
            /*
            DB::table('notifications')
            ->where('phone_number', $from)
            ->orderBy('id','desc')
            ->take(1)
            ->update(['reply' => $body]);
            */
        }
        return "message received";
    }
    private function sendMessage($from)
    {
        $account_sid = getenv("TWILIO_ACCOUNT_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_PHONE_NUMBER");
        $client = new Client($account_sid, $auth_token);

        $message = "Confirmation: You have accepted the invitation";
        $client->messages->create($data['phone'],
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
