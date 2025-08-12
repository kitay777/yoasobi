<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DevSeedController extends Controller
{
    public function seedNearby(Request $req)
    {
        
        $uid = Auth::id();
        // 自分の基準点（現在 DB に記録されている座標）を取得
        $me = DB::table('users')
            ->selectRaw('ST_Y(location) AS lat, ST_X(location) AS lng')
            ->where('id', $uid)
            ->first();

        if (!$me || $me->lat === null || $me->lng === null) {
            return response()->json(['ok'=>false, 'error'=>'your location not set'], 422);
        }

        // 半径（km）←デフォルト 20km。?radius_km= を渡せば変更可
        $radiusKm = (float) $req->input('radius_km', 20);
        $radiusM  = max(1, (int) round($radiusKm * 1000));

        $ids = range(2, 47);               // 対象ID
        $updated = 0;
        $lat0 = (float)$me->lng;
        $lng0 = (float)$me->lat;


        foreach ($ids as $id) {
            if ($id == $uid) continue;    // 自分はスキップ

            // 円内一様分布（メートル）→度に換算
            $bearing = 2 * M_PI * (mt_rand() / mt_getrandmax());
            $dist = $radiusM * sqrt(mt_rand() / mt_getrandmax()); // 一様に見えるよう sqrt

            // 緯度1度=約111,320m、経度は緯度依存
            $dLat = ($dist * cos($bearing)) / 111320.0;
            $cosLat = cos(deg2rad($lat0));
            $dLng = ($dist * sin($bearing)) / (111320.0 * max(0.000001, $cosLat));

            $lat = $lat0 + $dLat;
            $lng = $lng0 + $dLng;

            // role をランダムに
            $role = (mt_rand(0,1) === 0) ? 'cast' : 'advisor';
            $profile_photo_path = ($role === 'advisor') ? 'profile-photos/tRou5DkavXTZ7IuYPF6AtCHz69COEGxzYHwUh2ci.png' : 'profile-photos/XKPWvxpqqIciTr2yNuJieyuwhnc4oOcpyea8kZJT.png';
            $rrr = mt_rand(0,4);
            switch ($rrr) {
                case 0: $profile_photo_path = 'profile-photos/tRou5DkavXTZ7IuYPF6AtCHz69COEGxzYHwUh2ci.png'; break;
                case 1: $profile_photo_path = 'profile-photos/XKPWvxpqqIciTr2yNuJieyuwhnc4oOcpyea8kZJT.png'; break;
                case 2: $profile_photo_path = 'profile-photos/qSWvxJKUsftigGul4W3VdN8mlh60bRKtUYrmIMqK.png'; break;
                case 3: $profile_photo_path = 'profile-photos/FHFy2SJAPfNNf7vDWFKjFs1LD1GEfhH6eIAGHFyP.jpg'; break;
                case 4: $profile_photo_path = 'profile-photos/EOsqaabFVzdRSjLt0zgCr45xZJ6TcBj6fO0YELPd.jpg'; break;
            }
            // 位置・role・共有ON を更新（POINT は (lng,lat)！）
            $aff = DB::update(
                'UPDATE users
                   SET location = ST_SRID(POINT(?, ?), 4326),
                       share_location = 1,
                       role = ?,
                        profile_photo_path = ?,
                       location_updated_at = NOW()
                 WHERE id = ?',
                [$lng, $lat, $role, $profile_photo_path, $id]
            );

            $updated += $aff;
        }

        return response()->json([
            'ok' => true,
            'radius_km' => $radiusKm,
            'base' => ['lat'=>$lat0, 'lng'=>$lng0],
            'updated' => $updated,
        ]);
    }
}
