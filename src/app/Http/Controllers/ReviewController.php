<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    // 全ての口コミを表示
    public function index(Shop $shop)
    {
        $reviews = $shop->reviews()->with('user')->first();
        // $reviews = Review::where('shop_id', $shop->id)->first();
        return view('review_edit', compact('reviews', 'shop'));
    }
    // 特定の店舗のレビューを表示するメソッド
    public function showReviewsByShop($shopId)
    {
        // 店舗の存在確認と関連するレビューの取得
        $shop = Shop::with(['area', 'genre', 'reviews.user'])->findOrFail($shopId);
        $reviews = $shop->reviews;
        // return view('reviews.index', compact('shop', 'reviews'));
        return view('review_edit', compact('shop', 'reviews'));
    }

    //  口コミ投稿するための「フォーム表示」
    public function create(Shop $shop)
    {
        return view('review_create', compact('shop'));
        // return view('reviews.create', compact('shop'));
    }
    // 口コミをデータベースに保存するメソッド
    public function store(ReviewRequest $request, Shop $shop)
    {
        // 画像の保存処理
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        // 口コミの保存
        Review::create([
            'user_id' => Auth::id(),
            'shop_id' => $shop->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'image_path' => $imagePath,
        ]);
        return redirect()->route('reviews.by_shop', $shop->id);
    }

    // 口コミ編集フォームを表示
    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        $shop = $review->shop; // 口コミに関連する店舗を取得
        // return view('review_edit', compact('review', 'shop'));
        return view('reviews.edit', compact('review', 'shop'));
    }
    // 口コミを更新するメソッド
    public function update(ReviewRequest $request, Review $review)
    {
        // ポリシーで権限チェック
        $this->authorize('update', $review);
        
        $imagePath = $review->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        $review->update([
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'image_path' => $imagePath,
        ]);
        // 店舗詳細ページにリダイレクト
        return redirect()->route('shop.detail', $review->shop_id)->with('message', '口コミを更新しました');
    }

    // 口コミを削除するメソッド
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        // 口コミが属していた店舗IDを保存
        $shopId = $review->shop_id;

        $review->delete();

        return redirect()
            ->route('reviews.by_shop', ['shopId' => $shopId])
            ->with('message', '口コミを削除しました');
    }
}
