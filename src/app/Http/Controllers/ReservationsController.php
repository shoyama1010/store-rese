<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UpdateReservationRequest;

class ReservationsController extends Controller
{
    public function create(ReservationRequest $request)
    {
        Reservation::create([
            'date' => $request->date,
            'time' => $request->time,
            'user_num' => $request->user_num,
            'user_id' => Auth::id(),
            'shop_id' => $request->shop_id,
        ]);

        return view('reservation');
    }

    public function edit(Reservation $reservation)
    {
        // ログインユーザーが予約の所有者であることを確認
        abort_if(
            $reservation->user_id !== auth()->id(),
            403,
            'この予約は変更できません。'
        );

        // if ($reservation->user_id !== Auth::id()) {
        //     abort(403, 'この予約は変更できません。');
        // }
        $reservation->load('shop');

        return view('reservation_edit', compact('reservation'));
    }

    public function update(
        UpdateReservationRequest $request,
        Reservation $reservation
    ): RedirectResponse {
        // URLを直接変更して他人の予約を更新できないようにする
        abort_if(
            $reservation->user_id !== auth()->id(),
            403,
            'この予約は変更できません。'
        );

        $reservation->update([
            'date' => $request->date,
            'time' => $request->time,
            'user_num' => $request->user_num,
        ]);

        return redirect()
            ->route('mypage')
            ->with('message', '予約内容を変更しました。');
    }


    public function delete($reservation_id)
    {
        $reservation = Reservation::where('id', $reservation_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reservation->delete();

        session()->flash('fs_msg', '予約を削除しました。');

        return redirect('mypage');
    }
}
