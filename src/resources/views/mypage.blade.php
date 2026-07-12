@extends('layouts.app')

@section('main')
<div class="main">

  <h2 class="username">{{ $user->name }}さん</h2>

  <div class="flex between mypage">
    <!-- 左側 予約状況を表示-->
    <div class="status">
      <h3 class="status__ttl">予約状況</h3>

      <!-- 予約完了メッセージ -->
      @if (session('message'))
      <div class="mypage__message">
        {{ session('message') }}
      </div>
      @endif

      @foreach ($user->reservations as $reservation)
      <div class="status__card">
        <div class="flex align-items-center between status__card__top">
          <img src="/img/time.png" alt="time-icon" width="25px" height="25px" />
          <p>予約{{ $reservation->pivot->id }}</p>

          <form class="ml-a" action="{{ route('reserve.delete', ['reservation_id' => $reservation->pivot->id]) }}" method="POST">
            @csrf
            <input class="cancel" type="image" src="/img/cross.png" alt="送信する" width="25px" height="25px" onclick='return confirm("予約を取り消しますか？");'>
            <!-- // キャンセルボタンとして画像を使用。クリックすると確認メッセージが表示され、OKなら予約キャンセルを実行。 -->
          </form>
        </div>
        <table class="status__card__bottom">
          <tr>
            <td>Shop</td>
            <td>{{$reservation->name}}</td>
            <!-- // 予約した店舗の名前を表示。 -->
          </tr>
          <tr>
            <td>Date</td>
            <td>{{$reservation->pivot->date}}</td>
            <!-- // 予約の日付を表示。 -->
          </tr>
          <tr>
            <td>Time</td>
            <td>{{$reservation->pivot->time}}</td>
          </tr>
          <tr>
            <td>Number</td>
            <td>{{$reservation->pivot->user_num}}人</td>
          </tr>
        </table>

        <div class="status__card-actions">
          <a
            class="reservation-edit-btn"
            href="{{ route('reservation.edit', $reservation->pivot->id) }}">
            予約変更
          </a>

          

        </div>
      </div>
      @endforeach
    </div>

    <!-- 右側 お気に入り店舗リスト-->
    <div class="likes">
      <h3 class="likes__ttl">お気に入り店舗</h3>
      <div class="flex card-wrapper between wrap">
        @foreach($user->likes as $shop)
        <!-- // ユーザーが「いいね」した店舗リストをループして表示。`$user->likes` はお気に入りの店舗データを含む。 -->

        <div class="shop-card">
          <img class="shop-card__img" src="{!! $shop->image_url !!}" alt="shop-img" />

          <div class="shop-card__content">
            <h2 class="shop-card__content__ttl">{{$shop->name}}</h2>
            <!-- // 店舗名を表示。 -->

            <p class="shop-card__content__txt">
              #{{$shop->area->name}}&nbsp;#{{$shop->genre->name}}
              <!-- // 店舗のエリア名とジャンル名を表示。 -->
            </p>
            <div class="flex align-items-center between">
              <a class="shop-card__content__link" href="{!! '/detail/' . $shop->id !!}">
                詳しくみる
              </a>
              <!-- // 店舗の詳細ページへのリンクを表示。 -->

              @if( Auth::check() )
              <!-- // ユーザーがログインしている場合のみ、「いいね」ボタンを表示。 -->

              @if(count($shop->likes)==0)
              <!-- // ユーザーがその店舗に「いいね」していない場合。 -->

              <form method="POST" action="{{ route('like', ['shop_id' => $shop->id]) }}">
                @csrf
                <input class="shop-card__content__icon inactive" type="image" src="/img/unlike.png" alt="いいね" width="32px" height="32px">
                <!-- // いいねボタンとして「unlike」のアイコンを表示し、クリックすると「いいね」が登録される。 -->
              </form>
              @else
              @csrf
              <form class="ml-a" method="POST" action="{{ route('unlike', ['shop_id' => $shop->id]) }}">
                <input class="shop-card__content__icon inactive" type="image" src="/img/like.png" alt="いいねを外す" width="32px" height="32px">
                <!-- // すでに「いいね」している場合は、いいね解除のアイコンを表示し、クリックでいいねを外す。 -->
              </form>
              @endif
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection