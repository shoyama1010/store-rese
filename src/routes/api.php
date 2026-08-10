<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/admin/me', fn() =>
//     request()->user('admin')?->only(['id','name','email'])
// );

// まずは公開API（誰でもOK）
Route::get('/ping', fn() => response()->json([
    'ok' => true,
    'time' => now()->toISOString(),
]));

// 管理者の情報: admin ガード（セッション）で保護
Route::middleware('auth:admin')->get('/admin/me', function () {
    $user = Auth::guard('admin')->user();
    return $user
        ? $user->only(['id', 'name', 'email'])
        : response()->json([], 401);
    // return request()->user('admin')?->only(['id','name','email']);
});

// 認証が必要な管理ダッシュボード API
Route::get('/admin/dashboard', function () {
    return response()->json([
        'title' => '管理者用店舗予約管理ページ',
        'message' => 'ここで店舗の管理や予約の管理ができます。',
        'links' => [
            'csv' => '/admin/csv/import'
        ]
    ]);
});

Route::get('/shops', function () {
    $shops = Shop::with(['area', 'genre'])->get();

    return response()->json([
        'shops' => $shops,
    ]);
});

Route::get('/shops/{shop}', function (Shop $shop) {
    $shop->load(['area', 'genre']);

    return response()->json([
        'shop' => $shop,
    ]);
});