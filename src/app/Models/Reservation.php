<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'user_num',
        'user_id',
        'shop_id'
    ];
    // 一括代入を許可する属性を定義。これにより、`date`, `time`, `user_num`, `user_id`, `shop_id` のフィールドが一括代入される。

    public static function postReservation($request, $shop_id)
    // 引数$request: ユーザーから送られてくる予約に関するリクエストデータ（予約日、時間、人数、ユーザーID）。
    {
        // パラメータの作成: $param 配列を使って、リクエストから送信された予約情報と店舗IDをまとめます。
        $param = [
            "date" => $request->date,
            "time" => $request->time,
            "user_num" => $request->user_num,
            "user_id" => $request->user_id,
            "shop_id" => $shop_id,
        ];

        // 予約の作成
        $reservation = Reservation::create($param);
       
        return $reservation;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
