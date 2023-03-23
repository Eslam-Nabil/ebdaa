<?php

namespace App\Http\Controllers\App;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function GetMap(Request $request)
    {
        $credentials = $request->all();
        if (isset($credentials['lat']))
        {
            $lat = $credentials['lat'];
            $lng = $credentials['lng'];
        } 
        else
        {
            $lat = "30.8827553";
            $lng = "29.5782958";
        }        

        $user = Auth::user();

        $home = [];

        foreach ($user->children()->whereNotNull('lat')->whereNotNull('lng')->get() as $child)
        {            
            $home[] = [
                'lat' => $child->lat,
                'lng' => $child->lng
            ];
        }

        $home[] = ['lat' => $lat, 'lng' => $lng];

        return view('portal/buses/map', 
            [
                'lat' => $lat,
                'lng' => $lng, 
                'user' => $user, 
                'home' => $home,
            ]);
    }

    public function GetCurrentJourneys(Request $request)
    {
        $buses = Auth::user()
            ->children()
            ->where('available', 0)
            ->whereHas('s2j', function($query)
            {
                $query->whereNull('out_lng');
            })
            ->whereHas('buses.journeys', function($query)
            {
                $query->whereNull('ended')->whereNotNull('started');
            })
            ->with(['buses' => function($query)
            {
                $query->whereHas('journeys');
            }, 
            'buses.journeys' => function($query)
            {
                $query->latest()->take(1);
            }, 's2j' => function($query){
                $query->latest()->take(1);
            }])
            ->get();

        return response()->json($buses);
    }

    public function SetHomeLocation(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'lat' => 'required',
            'lng' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return abort(400, "invalid parameters");
        }

        $children = Auth::user()->children()->get();
        foreach($children as $child)
        {
            $child->lat = $credentials['lat'];
            $child->lng = $credentials['lng'];
            $child->save();
        }

        return response()->json(['lat' => $credentials['lat'], 'lng' => $credentials['lng']]);
    }
}