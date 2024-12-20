@extends('layouts.admin_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_attendance_detail.css')}}">
@endsection

@section('content')
<div class="admin-attendance-detail_content">
    <div class="admin-attendance-detail_title">
        <h2 class="admin-attendance-detail_title-text">勤怠情報</h2>
    </div>
    <div class="admin-attendance-detail_form">
        <form action="/admin/attendance/update" method="post">
            @csrf
            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
            <table class="admin-attendance-detail_form-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>
                        <input type="text" name="attendance_year" placeholder="{{ $attendance->formattedYear }}" value="{{ old('attendance_year' , $attendance->formattedYear ) }}">
                    </td>
                    <td></td>
                    <td>
                        <input type="text" name="attendance_date" placeholder="{{ $attendance->formattedDate }}" value="{{ old('attendance_date' , $attendance->formattedDate ) }}">
                    </td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="text" name="clock_in_time" placeholder="{{ $attendance->clock_in_time }}" value="{{ old('clock_in_time' , $attendance->clock_in_time ) }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="text" name="clock_out_time" placeholder="{{ $attendance->clock_out_time }}" value="{{ old('clock_out_time' , $attendance->clock_out_time ) }}">
                    </td>
                </tr>
                @error('clock_in_time')
                <tr>
                    <th></th>
                    <td>{{ $message }}</td>
                </tr>
                @enderror
                @error('clock_out_time')
                <tr>
                    <th></th>
                    <td>{{ $message }}</td>
                </tr>
                @enderror
                @foreach($rests as $index => $rest)
                <tr>
                    <th>休憩</th>
                    <td>
                        <input type="text" name="rest_start_time[]" placeholder="{{ $rest->rest_start_time }}" value="{{ old('rest_start_time.' . $index , $rest->rest_start_time) }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="text" name="rest_end_time[]" placeholder="{{ $rest->rest_end_time }}" value="{{ old('rest_end_time.' . $index , $rest->rest_end_time) }}">
                    </td>
                </tr>
                @error('rest_start_time.' . $index)
                <tr>
                    <th></th>
                    <td>{{ $message }}</td>
                </tr>
                @enderror
                @error('rest_end_time.' . $index)
                <tr>
                    <th></th>
                    <td>{{ $message }}</td>
                </tr>
                @enderror
                @endforeach
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="late_reason"></textarea>
                    </td>
                </tr>
                @error('late_reason')
                <tr>
                    <th></th>
                    <td>{{ $message }}</td>
                </tr>
                @enderror
            </table>
            @if($attendance->approval_status == '')
                <div class="admin-attendance-detail_button">
                    <button class="admin-attendance-detail_button-submit" type="submit">修正</button>
                </div>
            @endif
            @if($attendance->approval_status == "approval");
                <div class="admin-attendance-detail_approval">
                    <span class="admin-attendance-detail_button-submit">承認済み</span>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection