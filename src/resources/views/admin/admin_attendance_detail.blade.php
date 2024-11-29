@extends('layouts.admin_app')

@section('css')

@endsection

@section('content')
<div class="admin-attendance-detail_content">
    <div class="admin-attendance-detail_form">
        <form action="" method="post">
            @csrf
            <table>
                <tr>
                    <th>名前</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>
                        <input type="text" name="attendance_year" placeholder="{{ $attendance->formattedYear }}">
                    </td>
                    <td></td>
                    <td>
                        <input type="text" name="attendance_date" placeholder="{{ $attendance->formattedDate }}">
                    </td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="text" name="clock_in_time" placeholder="{{ $attendance->clock_in_time }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="text" name="clock_out_time" placeholder="{{ $attendance->clock_out_time }}">
                    </td>
                </tr>
                @foreach($rests as $rest)
                <tr>
                    <th>休憩</th>
                    <td>
                        <input type="text" name="rest_start_time" placeholder="{{ $rest->rest_start_time }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="text" name="rest_end_time" placeholder="{{ $rest->rest_end_time }}">
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="late_reason" id=""></textarea>
                    </td>
                </tr>
            </table>
            <div class="admin-attendance-detail_button">
                <button class="admin-attendance-detail_button-submit" type="submit">修正</button>
            </div>
        </form>
    </div>
</div>
@endsection