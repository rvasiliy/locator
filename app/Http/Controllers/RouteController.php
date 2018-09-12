<?php

namespace App\Http\Controllers;

use App\User;
use App\UserCoordinate;
use Carbon\Carbon;
use FarhanWazir\GoogleMaps\GMaps;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $users = User::all([
            'id',
            'name',
            'email'
        ]);

        $coords = UserCoordinate::all(['user_id', 'longitude', 'latitude', 'updated_at'])
            ->where('updated_at', '>=', Carbon::today())
            ->groupBy('user_id')
            ->transform(function ($item) {
                return $item->last();
            });

        $gmap = new GMaps();
        $gmap->initialize([
            'center' => 'Russia, Moscow'
        ]);

        foreach ($coords as $coord) {
            $gmap->add_marker([
                'position' => "{$coord->latitude},{$coord->longitude}",
                'infowindow_content' => "Name: {$coord->user->name}<br> Email: {$coord->user->email}<br> Phone: {$coord->user->detail->phone}",
            ]);
        }

        $map = $gmap->create_map();

        return view('route.index', compact('users', 'coords', 'map'));
    }

    public function detail(Request $request)
    {
        return view('route.detail');
    }
}
