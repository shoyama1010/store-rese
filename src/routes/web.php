<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ShopsController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MultiLoginController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminOwnerController;
use App\Http\Controllers\Admin\CsvImportController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

/*
|--------------------------------------------------------------------------
| 一般公開ページ
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopsController::class, 'index'])->name('root');
Route::get('/shops', [ShopsController::class, 'index'])->name('shops.index');
Route::get('/shops/sort', [ShopsController::class, 'sort'])->name('shops.sort');
Route::get('/detail/{shop_id}', [ShopsController::class, 'detail'])->name('shop.detail');
Route::get('/search', [ShopsController::class, 'search'])->name('shop.search');

/*
|--------------------------------------------------------------------------
| 一般ユーザー認証後
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/thanks', function () {
        return view('thanks');
    })->name('thanks');

    Route::get('/mypage', [UsersController::class, 'mypage'])->name('mypage');

    Route::post('/like/{shop_id}', [FavoriteController::class, 'create'])->name('like');
    Route::post('/unlike/{shop_id}', [FavoriteController::class, 'delete'])->name('unlike');

    Route::post('/reservation', [ReservationsController::class, 'create'])->name('reserve.create');
    Route::post('/reserve/{reservation_id}', [ReservationsController::class, 'delete'])->name('reserve.delete');
    Route::get(
        '/reservation/{reservation}/edit',
        [ReservationsController::class, 'edit']
    )->name('reservation.edit');
    Route::put(
        '/reservation/{reservation}',
        [ReservationsController::class, 'update']
    )->name('reservation.update');

    Route::get('/shop/{shopId}/reviews', [ReviewController::class, 'showReviewsByShop'])->name('reviews.by_shop');
    Route::get('/shops/{shop}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/shops/{shop}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/checkout', [PaymentController::class, 'showCheckoutForm'])->name('checkout.form');
    Route::post('/checkout', [PaymentController::class, 'processPayment'])->name('checkout.process');
});


/*
|--------------------------------------------------------------------------
| マルチログイン
|--------------------------------------------------------------------------
*/
Route::get('/multi/login', [MultiLoginController::class, 'showLoginForm'])->name('multi.login.form');
Route::post('/multi/login', [MultiLoginController::class, 'login'])->name('multi.login');

/*
|--------------------------------------------------------------------------
| 管理者ログイン・管理者画面
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // 管理者ログイン
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // 管理者ログイン後
    Route::middleware('auth:admin')->group(function () {
        // ダッシュボード
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // 店舗代表者管理
        Route::resource('owners', AdminOwnerController::class);

        // 店舗代表者関連
        Route::get('/owners/shops/{shop_id}', [AdminOwnerController::class, 'show'])->name('owners.shops.show');
        Route::get('/owners/{id}/edit-status', [AdminOwnerController::class, 'editStatus'])->name('owners.edit_status');
        Route::put('/owners/{id}/update-status', [AdminOwnerController::class, 'updateStatus'])->name('owners.update_status');
        Route::get('/owners/{id}/shops', [AdminOwnerController::class, 'showOwnerShops'])->name('owners.shops');

        // 管理者用：店舗別口コミ一覧・口コミ削除
        Route::get('/shops/reviews', [AdminController::class, 'reviewShops'])->name('reviews.shops');
        Route::get('/shops/{shop}/reviews', [AdminController::class, 'shopReviews'])->name('reviews.shop');
        Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');

        // CSV
        Route::get('/upload-csv', [CsvImportController::class, 'showUploadForm'])->name('upload_csv.form');
        Route::post('/upload-csv', [CsvImportController::class, 'importCsv'])->name('upload_csv');
    });
});

/*
|--------------------------------------------------------------------------
| 店舗代表者画面
|--------------------------------------------------------------------------
*/
Route::prefix('owner')->name('owner.')->middleware('auth:owner')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

    Route::get('/shop/edit', [OwnerController::class, 'editShop'])->name('shop.edit');
    Route::put('/shop/update', [OwnerController::class, 'updateShop'])->name('shop.update');

    Route::get('/reservations', [OwnerController::class, 'reservations'])
        ->name('reservations.index');

    Route::post('/reservations/{reservation}/send-mail', [OwnerController::class, 'sendReservationMail'])
        ->name('reservations.send_mail');

    // Route::resource('shops', ShopsController::class);
    // Route::resource('reservations', ReservationsController::class);
});

require __DIR__ . '/auth.php';
