<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in_time' => ['date_format:H:i:s'],
            'clock_out_time' => ['date_format:H:i:s'],
            'rest_start_time.*' => ['date_format:H:i:s'],
            'rest_end_time.*' => ['date_format:H:i:s'],
            'late_reason' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'clock_in_time.date_format' => '勤務開始時間の形式が正しくありません',
            'clock_out_time.date_format' => '勤務終了時間の形式が正しくありません',
            'rest_start_time.*.date_format' => '休憩開始時間の形式が正しくありません',
            'rest_end_time.*.date_format' => '休憩終了時間の形式が正しくありません',
            'late_reason.required' => '備考を記入してください'
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clockInTime = new Carbon($this->clock_in_time);
            $clockOutTime = new Carbon($this->clock_out_time);
    
            if ($clockInTime >= $clockOutTime) {
                $validator->errors()->add('clock_in_time', '出勤時間もしくは退勤時間が不適切な値です。');
            }
    
            if (isset($this->rest_start_time) && is_array($this->rest_start_time)) {
                foreach ($this->rest_start_time as $index => $start_time) {
                    $restStartTime = new Carbon($start_time);
                    $restEndTime = new Carbon($this->rest_end_time[$index]);
    
                    if ($restStartTime >= $clockOutTime) {
                        $validator->errors()->add('rest_start_time.' . $index, '休憩時間が勤務時間外です。');
                    }
                    if ($restStartTime <= $clockInTime) {
                        $validator->errors()->add('rest_start_time.' . $index, '休憩時間が勤務時間外です。');
                    }
                    if ($restEndTime >= $clockOutTime) {
                        $validator->errors()->add('rest_end_time.' . $index, '休憩時間が勤務時間外です。');
                    }
                    if ($restStartTime >= $restEndTime) {
                        $validator->errors()->add('rest_start_time.' . $index, '休憩開始時間は休憩終了時間より前である必要があります。');
                    }
                }
            }
        });
    }
    
}
