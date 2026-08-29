<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'genre_id',
        'name',
        'description',
        'image_url',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
        // 店舗がどのエリアに属しているかを定義するリレーション。`Shop` は1つの `Area` に属しているので `belongsTo` を使う。
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
        // 店舗がどのジャンルに属しているかを定義するリレーション。`Shop` は1つの `Genre` に属しているので `belongsTo` を使う。
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public static function getShops()
    {
        $shops = Shop::with('area', 'genre')->with(
            'likes',
            function ($query) {
                $query->where('user_id', Auth::id());
            }

        )->get();

        return $shops;
    }

    
    public static function conditionFormat($conditions)
    {
        $result = "";
        foreach ($conditions as $index => $condition) {
            if ($index == 0) {
                $result =  $condition;
            } else {
                $result =  $result . "・" . $condition;
            }
        }
        return $result;
        // フォーマットされた条件テキストを返す。
    }

    public static function searchShops($area_name, $genre_name, $keyword)
    {
        $query = Shop::query();
        // 店舗データを検索するためのクエリビルダーを初期化。
        $conditions = array();
        // 検索条件を保存するための配列。

        if (!empty($area_name)) {
            array_push($conditions, $area_name);
            // エリア名が指定されている場合、条件に追加。

            $query->whereHas('area', function ($query) use ($area_name) {
                $query->where('name', $area_name);
            });
        } else {
            $query->with('area');
        }

        if (!empty($genre_name)) {
            array_push($conditions, $genre_name);
            // ジャンル名が指定されている場合、条件に追加。

            $query->whereHas('genre', function ($query) use ($genre_name) {
                $query->where('name', $genre_name);
                // `genre` リレーションに基づいて、指定されたジャンル名の店舗だけを取得する条件を追加。
            });
        } else {
            $query->with('genre');
            // ジャンル名が指定されていない場合、すべての店舗のジャンル情報も一緒に取得。
        }

        if (!empty($keyword)) {
            array_push($conditions, $keyword);
            // キーワードが指定されている場合、条件に追加。
            $query->where('name', 'like', "%$keyword%");
            // 店舗名に指定されたキーワードが含まれるかどうかを条件に追加。部分一致検索。
        }

        $shops = $query->get();
        // 条件に基づいてフィルタリングされた店舗データを取得

        $text = self::conditionFormat($conditions);
        // 検索条件をフォーマットしてテキストとして整形
        return compact('shops', 'text');
        // 検索結果として、店舗データと検索条件のテキストを返す
    }

    // 口コミの関係性を追加
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
