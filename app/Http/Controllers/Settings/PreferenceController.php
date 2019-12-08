<?php

namespace App\Http\Controllers\Settings;

use App\Preference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PreferenceController extends Controller
{
    /**
     * Update the user's preferences.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatecategory($name, Request $request)
    {
        $user = $request->user();

        $id = DB::table('categories')->where('category_name', $name)->first();

        //$preference = DB::table('preferences')->where('user_id', $id)->first();

        DB::table('preferences')->insert(['user_id' => $user->id, 'category' => $id->id]);

    }

    public function deletecategory($name, Request $request)
    {
        $user = $request->user();

        $id = DB::table('categories')->where('category_name', $name)->first();

        $whereArray = array('user_id' => $user->id,'category' => $id->id);

        $query = DB::table('preferences');
        foreach($whereArray as $field => $value) {
            $query->where($field, $value);
        }
        return $query->delete();
    }
    public function getpreferences(Request $request)
    {
        $user = $request->user();

        $preferences = DB::table('preferences')->where('user_id', $user->id)->get();
        return response()->json($preferences);
    }

}