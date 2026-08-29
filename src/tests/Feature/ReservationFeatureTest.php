<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインユーザーは予約できる()
    {
        /** @var User $user */
        $user = User::factory()->create();

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

        $response = $this->actingAs($user)->post(
            route('reserve.create'),
            [
                'shop_id' => $shop->id,
                'date' => now()->addDay()->format('Y-m-d'),
                'time' => '18:00',
                'user_num' => 2,
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'user_num' => 2,
        ]);
    }

    public function test_未ログインユーザーは予約できない()
    {
        $response = $this->post(
            route('reserve.create'),
            [
                'shop_id' => 1,
                'date' => now()->addDay()->format('Y-m-d'),
                'time' => '18:00',
                'user_num' => 2,
            ]
        );
        $response->assertRedirect();
    }

    public function test_自分の予約は変更できる()
    {
        /** @var User $user */
        $user = User::factory()->create();

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

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '18:00',
            'user_num' => 2,
        ]);

        $response = $this->actingAs($user)->put(
            route('reservation.update', $reservation),
            [
                'date' => now()->addDays(2)->format('Y-m-d'),
                'time' => '19:00',
                'user_num' => 4,
            ]
        );

        $response->assertRedirect(route('mypage'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'time' => '19:00:00',
            'user_num' => 4,
        ]);
    }

    public function test_他人の予約は変更できない()
    {
        /** @var User $owner */
        $owner = User::factory()->create();
        /** @var User $otherUser */
        $otherUser = User::factory()->create();

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

        $reservation = Reservation::create([
            'user_id' => $owner->id,
            'shop_id' => $shop->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '18:00',
            'user_num' => 2,
        ]);

        $response = $this->actingAs($otherUser)->put(
            route('reservation.update', $reservation),
            [
                'date' => now()->addDays(2)->format('Y-m-d'),
                'time' => '19:00',
                'user_num' => 4,
            ]
        );

        $response->assertStatus(403);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'time' => '18:00:00',
            'user_num' => 2,
        ]);
    }
}
