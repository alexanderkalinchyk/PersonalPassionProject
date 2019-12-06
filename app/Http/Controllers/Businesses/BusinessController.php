<?php

namespace App\Http\Controllers\Businesses;

use App\Business;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BusinessController extends Controller
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
        //return view('createbusiness');
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
    public function showbusinesses($location, $radius, $offset)
    {
        $location = str_replace(' ', '+', $location);

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bearer xNvYySEOsLIwtxMvP-wG34D9IueBb5m5Fk8edWjcsge-hrLBCVj_0yH1wSIZblF5jtE3dn_12Vb50RCU9KMrg4xPOnbJh2RUl_5ZE6QFtsv9cN59Jn_ssCR53y7ZXXYx"
            ]
        ];

        $context = stream_context_create($opts);

        // Open the file using the HTTP headers set above
        $file = file_get_contents('https://api.yelp.com/v3/businesses/search?&categories=restaurants&location='.$location.'&radius='.$radius.'&offset='.$offset.'&limit=5', false, $context);

        return $file;

        //return response()->json($file);
    }
    public function getbusinessbyid($id)
    {
       $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bearer xNvYySEOsLIwtxMvP-wG34D9IueBb5m5Fk8edWjcsge-hrLBCVj_0yH1wSIZblF5jtE3dn_12Vb50RCU9KMrg4xPOnbJh2RUl_5ZE6QFtsv9cN59Jn_ssCR53y7ZXXYx"
            ]
        ];

        $context = stream_context_create($opts);

        // Open the file using the HTTP headers set above
        $file = file_get_contents('https://api.yelp.com/v3/businesses/'.$id, false, $context);

        return $file;

        //return response()->json($file);
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