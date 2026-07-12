<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'time' => [
                'required',
                'date_format:H:i',
            ],
            'user_num' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => '予約日を入力してください。',
            'date.date' => '予約日を正しく入力してください。',
            'date.after_or_equal' => '本日以降の日付を選択してください。',

            'time.required' => '予約時間を入力してください。',
            'time.date_format' => '予約時間を正しく入力してください。',

            'user_num.required' => '予約人数を入力してください。',
            'user_num.integer' => '予約人数は整数で入力してください。',
            'user_num.min' => '予約人数は1人以上で入力してください。',
            'user_num.max' => '予約人数は20人以下で入力してください。',
        ];
    }
}
