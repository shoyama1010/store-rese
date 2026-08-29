<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Owner;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class OwnerAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テスト用の店舗代表者と店舗を作成
     */
    private function createOwnerWithShop(): array
    {
        $area = Area::create([
            'name' => '東京都',
        ]);

        $genre = Genre::create([
            'name' => '寿司',
        ]);

        $shop = Shop::create([
            'name' => 'テスト店舗',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'description' => 'テスト店舗です',
            'image_url' => 'https://example.com/shop.jpg',
        ]);

        $owner = Owner::create([
            'shop_id' => $shop->id,
            'name' => '店舗代表者',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
        ]);

        return compact('owner', 'shop');
    }

    /**
     * 未ログインでは店舗代表者の予約一覧にアクセスできない
     */
    public function test_未ログインでは店舗代表者の予約一覧にアクセスできない()
    {
        $response = $this->get(
            route('owner.reservations.index')
        );

        $response->assertRedirect();
    }

    /**
     * 店舗代表者は自店舗の予約一覧にアクセスできる
     */
    public function test_店舗代表者は自店舗の予約一覧にアクセスできる()
    {
        $data = $this->createOwnerWithShop();

        $response = $this
            ->actingAs($data['owner'], 'owner')
            ->get(route('owner.reservations.index'));

        $response->assertStatus(200);

        $response->assertSee('テスト店舗');
    }

    /**
     * 店舗代表者は自店舗の編集画面にアクセスできる
     */
    public function test_店舗代表者は自店舗の編集画面にアクセスできる()
    {
        $data = $this->createOwnerWithShop();

        $response = $this
            ->actingAs($data['owner'], 'owner')
            ->get(route('owner.shop.edit'));

        $response->assertStatus(200);

        $response->assertSee('テスト店舗');
    }

    /**
     * 一般ユーザーとしてログインしていても
     * 店舗代表者画面にはアクセスできない
     */
    public function test_一般ユーザーは店舗代表者画面にアクセスできない()
    {
        $response = $this->get(
            route('owner.dashboard')
        );

        $response->assertRedirect();
    }
}
