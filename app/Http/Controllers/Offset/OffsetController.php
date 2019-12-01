<?php

namespace App\Http\Controllers\Offset;

use App\Offset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OffsetController extends Controller
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
        return view('createoffset');
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
    public function offsetbyuserid($id)
    {
        $user = DB::table('offset')->where('user_id', $id)->first();
        if ($user === null) {
            DB::table('offset')->insert(['user_id' => $id, 'offset' => 0]);
            $offset = DB::table('offset')->where('user_id', $id)->get();
            return response()->json($offset);
        }
        else{
            $offset = DB::table('offset')->where('user_id', $id)->get();
            return response()->json($offset);
        }
    }
    public function updatebyuserid($id)
    {
        $offset =  DB::table('offset')
        ->where('user_id', $id)
        ->increment('offset');

        return response()->json($offset);
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
