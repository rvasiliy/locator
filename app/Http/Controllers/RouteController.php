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
            $card = "Name: {$coord->user->name}<br> Email: {$coord->user->email}<br> Phone: {$coord->user->detail->phone}<br><br>";
            $card .= '<a href="' . route('route_detail', ['user_id' => $coord->user_id]) . '">Route by current day</a>';

            $gmap->add_marker([
                'position' => "{$coord->latitude},{$coord->longitude}",
                'infowindow_content' => $card,
            ]);
        }

        $map = $gmap->create_map();

        return view('route.index', compact('users', 'coords', 'map'));
    }

    public function detail(Request $request)
    {
        $userId = $request->route('user_id');

        $user = User::all(['id', 'name', 'email'])
            ->where('id', '=', $userId)
            ->first();

        $coords = UserCoordinate::all(['user_id', 'longitude', 'latitude', 'updated_at'])
            ->where('updated_at', '>=', Carbon::today())
            ->where('user_id', '=', $userId);

        $gmap = new GMaps();
        $gmap->initialize([
            'center' => 'Russia, Moscow'
        ]);

        $arrCoords = [
            'points' => [],
        ];

        foreach ($coords as $coord) {
            $arrCoords['points'][] = "{$coord->latitude},{$coord->longitude}";
        }

        $gmap->add_polyline($arrCoords);

        if (!empty($arrCoords)) {
            $lastCoord = $coords->last();
            $card = $card = "Name: {$lastCoord->user->name}<br> Email: {$lastCoord->user->email}<br> Phone: {$lastCoord->user->detail->phone}";

            $gmap->add_marker([
                'position' => "{$lastCoord->latitude},{$lastCoord->longitude}",
                'infowindow_content' => $card,
            ]);
        }

        $map = $gmap->create_map();

        return view('route.detail', compact('map', 'user'));
    }
}
