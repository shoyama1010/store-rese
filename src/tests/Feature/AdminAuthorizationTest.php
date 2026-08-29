<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テスト用管理者を作成
     */
    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'テスト管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * 未ログインユーザーは管理者ダッシュボードへ
     * アクセスできない
     */
    public function test_未ログインでは管理者画面にアクセスできない()
    {
        $response = $this->get(
            route('admin.dashboard')
        );

        $response->assertRedirect();
    }

    /**
     * 管理者は管理者ダッシュボードへアクセスできる
     */
    public function test_管理者は管理者画面にアクセスできる()
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /**
     * 一般ユーザーとしてログインしていても
     * 管理者画面にはアクセスできない
     */
    public function test_一般ユーザーは管理者画面にアクセスできない()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertRedirect();
    }

    /**
     * 店舗代表者としてログインしていても
     * 管理者画面にはアクセスできない
     */
    public function test_店舗代表者は管理者画面にアクセスできない()
    {
        $owner = Owner::create([
            'name' => 'テスト店舗代表者',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this
            ->actingAs($owner, 'owner')
            ->get(route('admin.dashboard'));

        $response->assertRedirect();
    }

    /**
     * 管理者は店舗代表者管理画面へアクセスできる
     */
    public function test_管理者は店舗代表者管理画面にアクセスできる()
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->get(route('admin.owners.index'));

        $response->assertStatus(200);
    }
}
