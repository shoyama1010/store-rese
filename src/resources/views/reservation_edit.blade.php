@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation_edit.css') }}">
@endsection

@section('main')
<div class="reservation-edit">
  <div class="reservation-edit__inner">

    <h2 class="reservation-edit__title">
      予約内容変更
    </h2>

    <div class="reservation-edit__shop">
      店舗名：{{ $reservation->shop->name }}
    </div>

    @if ($errors->any())
    <ul class="error__lists">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
    @endif

    <form
      class="reservation-edit__form"
      action="{{ route('reservation.update', $reservation->id) }}"
      method="POST">
      @csrf
      @method('PUT')

      {{-- 予約日 --}}
      <div class="reservation-edit__group">
        <label for="date">予約日</label>

        <input
          id="date"
          class="reservation-edit__date-input"
          type="date"
          name="date"
          min="{{ now()->format('Y-m-d') }}"
          value="{{ old('date', $reservation->date) }}">
      </div>

      {{-- 予約時間 --}}
      <div class="reservation-edit__group">
        <label for="time">予約時間</label>

        <select id="time" name="time">
          @foreach ([
          '11:00',
          '12:00',
          '13:00',
          '14:00',
          '15:00',
          '16:00',
          '17:00',
          '18:00',
          '19:00',
          '20:00',
          '21:00',
          '22:00'
          ] as $time)
          <option
            value="{{ $time }}"
            {{ old('time', substr($reservation->time, 0, 5)) === $time ? 'selected' : '' }}>
            {{ $time }}
          </option>
          @endforeach
        </select>
      </div>

      {{-- 予約人数 --}}
      <div class="reservation-edit__group">
        <label for="user_num">予約人数</label>

        <select id="user_num" name="user_num">
          @for ($number = 1; $number <= 7; $number++)
            <option
            value="{{ $number }}"
            {{ (int) old('user_num', $reservation->user_num) === $number ? 'selected' : '' }}>
            {{ $number }}人
            </option>
            @endfor
        </select>
      </div>

      <div class="reservation-edit__buttons">
        <a
          class="reservation-edit__back"
          href="{{ route('mypage') }}">
          戻る
        </a>

        <button
          class="reservation-edit__submit"
          type="submit">
          変更する
        </button>
      </div>
    </form>
  </div>
</div>
@endsection