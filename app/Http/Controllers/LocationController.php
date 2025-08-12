<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class LocationController extends Controller
{
    public function update(Request $req)
    {
        $data = $req->validate([
            'lat' => ['required','numeric'],
            'lng' => ['required','numeric'],
            'share_location' => ['sometimes','boolean'],
        ]);
        Log::info('Location update request data: ', $data);
                $lat   = (float) $data['lat'];   // 緯度
                $lng   = (float) $data['lng'];   // 経度
                $share = $req->boolean('share_location', true);
        Log::info('Location update request data: ', [
            'lng' => $lng,
            'lat' => $lat,
            'share_location' => $share,
        ]);
        // POINT は (lng, lat) の順！
        DB::update(
            'UPDATE users
             SET location = ST_SRID(POINT(?, ?), 4326),
                 share_location = ?,
                 location_updated_at = NOW()
             WHERE id = ?',
            [$lng, $lat, $share, Auth::id()]
        );

        return response()->json(['ok' => true]);
    }



    public function nearby(Request $req)
    {
        $lat   = (float)$req->query('lat');
        $lng   = (float)$req->query('lng');
        $rKm   = (float)$req->query('radius_km', 3.0);
        $limit = (int)$req->query('limit', 50);

        if (!is_finite($lat) || !is_finite($lng)) {
            return response()->json(['ok'=>false,'error'=>'lat/lng required'], 422);
        }

        $uid     = Auth::id();
        $meters  = (int) round($rKm * 1000);

        $users = DB::table('users')
            ->select([
                'id','name','profile_photo_path','role',
                DB::raw('ST_Y(location) AS lat'),
                DB::raw('ST_X(location) AS lng'),
                DB::raw('ST_Distance_Sphere(location, ST_SRID(POINT(?, ?), 4326)) AS distance_m'),
                'location_updated_at',
            ])
            ->addBinding([$lng, $lat]) // ↑ SELECT 内の POINT(?, ?) 用
            ->where('share_location', true)
            ->whereNotNull('location')
            ->where('id', '!=', $uid)
            ->whereRaw(
                'ST_Distance_Sphere(location, ST_SRID(POINT(?, ?), 4326)) <= ?',
                [$lng, $lat, $meters] // ← ここは 3 つだけ
            )
            ->orderBy('distance_m')
            ->limit($limit)
            ->get();

            $users = $users->map(function ($u) {
                // profile_photo_path があれば /storage/... のURLを作る
                if (!empty($u->profile_photo_path)) {
                    $u->avatar_url = Storage::disk('public')->url($u->profile_photo_path);
                } else {
                    $u->avatar_url = '/images/avatar-default.png'; // デフォルト画像
                }
                return $u;
            });
        return response()->json(['ok'=>true, 'count'=>$users->count(), 'users'=>$users]);
    }

    public function me()
    {
        $row = DB::table('users')->select(
            DB::raw('ST_Y(location) as lat'),
            DB::raw('ST_X(location) as lng'),
            'location_updated_at'
        )->where('id', Auth::id())->first();

        if (!$row || $row->lat === null || $row->lng === null) {
            return response()->json(['ok' => false, 'error' => 'no_location'], 404);
        }

        return response()->json([
            'ok' => true,
            'lat' => (float)$row->lat,
            'lng' => (float)$row->lng,
            'updated_at' => $row->location_updated_at,
        ]);
    }


}
