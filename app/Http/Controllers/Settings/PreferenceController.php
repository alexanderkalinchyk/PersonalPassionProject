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

    public function updatelocation($location, Request $request)
    {
        $user = $request->user();

        $get = DB::table('preferences')->where('user_id', $user->id)->first();
        if(!$get){
            DB::table('preferences')->insert(['user_id' => $user->id, 'location' => $location]);
        }
        else{
            DB::table('preferences')
            ->where('user_id', $user->id)
            ->update(['location' => $location]);
        }


    }
    public function getradius(Request $request)
    {
        $user = $request->user();

        //$preferences = DB::table('preferences')->where('user_id', $user->id)->get();
        $preferences = DB::table('preferences')
        ->select(['radius'])
        ->where('user_id', $user->id)
        ->get();
        return response()->json($preferences);
    }
    public function updateradius($radius, Request $request)
    {
        $user = $request->user();

        DB::table('preferences')
        ->where('user_id', $user->id)
        ->update(['radius' => $radius]);
    }
    public function insertradius($radius, Request $request){
        $user = $request->user();
        DB::table('preferences')->insert(['user_id' => $user->id, 'radius' => $radius]);
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

        //$preferences = DB::table('preferences')->where('user_id', $user->id)->get();
        $preferences = DB::table('preferences')
        ->select(['preferences.radius', 'preferences.location', 'preferences.category', 'categories.category_name'])
        ->leftJoin('categories', 'preferences.category', '=', 'categories.id')
        ->where('preferences.user_id', $user->id)
        ->get();
        return response()->json($preferences);
    }

}
