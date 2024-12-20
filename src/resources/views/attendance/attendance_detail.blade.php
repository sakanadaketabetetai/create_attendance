@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_detail.css') }}">
@endsection

@section('content')
<div class="attendance-detail_content">
    <div class="attendance-detail_title">
        <h2>勤怠詳細</h2>
    </div>
    @if($attendance->approval_status == 'approval' || empty($attendance->approval_status))
    <div class="attendance-detail_form">
        <form action="/attendance/stamp_correction_request" method="post">
            @csrf
            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
            <table class="attendance-detail_form-table">
                <tr>
                    <th>名前</th>
                    <td>{{ $user->name }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>
                        <input type="text" name="attendance_year" placeholder="{{ $attendance->formattedYear }}" value="{{ old( 'attendance_year', $attendance->formattedYear ) }}">
                    </td>
                    <td></td>
                    <td>
                        <input type="text" name="attendance_date" placeholder="{{ $attendance->formattedDate }}" value="{{ old( 'attendance_date', $attendance->formattedDate ) }}">
                    </td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="text" name="clock_in_time" placeholder="{{ $attendance->clock_in_time }}" value="{{ old( 'clock_in_time', $attendance->clock_in_time ) }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="text" name="clock_out_time" placeholder="{{ $attendance->clock_out_time }}" value="{{ old( 'clock_out_time', $attendance->clock_out_time ) }}">
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
                @foreach ($rests as $index => $rest)
                <tr>
                    <th>休憩</th>
                    <td>
                        <input type="text" name="rest_start_time[]" placeholder="{{ $rest->rest_start_time }}" value="{{ old('rest_start_time.' . $index , $rest->rest_start_time ) }}">
                    </td>
                    <td>～</td>
                    <td>
                        <input type="text" name="rest_end_time[]" placeholder="{{ $rest->rest_end_time }}" value="{{ old('rest_end_time.' . $index , $rest->rest_end_time ) }}">
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
                        <textarea name="late_reason" id=""></textarea>
                    </td>
                </tr>
                @error('late_reason')
                <tr>
                    <th></th>
                    <td>{{ $message }}</td>
                </tr>
                @enderror
            </table>
            <div class="attendance-detail_button">
                <button class="attendance-detail_button-submit" type="submit">修正</button>
            </div>
        </form>
    </div>
    @endif
    @if($attendance->approval_status == 'pending')
    <div class="attendance-detail_table">
        <table class="attendance-detail_form-table">
            <tr>
                <th>名前</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    {{ $attendance->formattedYear }}
                </td>
                <td></td>
                <td>
                    {{ $attendance->formattedDate }}
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    {{ $attendance->clock_in_time }}
                </td>
                <td>～</td>
                <td>
                    {{ $attendance->clock_out_time }}
                </td>
            </tr>
            @foreach ($rests as $rest)
            <tr>
                <th>休憩</th>
                <td>
                    {{ $rest->rest_start_time }}
                </td>
                <td>～</td>
                <td>
                    {{ $rest->rest_end_time }}
                </td>
            </tr>
            @endforeach
            <tr>
                <th>備考</th>
                <td>
                    <span class="">{{ $approval_request->late_reason }}</span>
                </td>
            </tr>
        </table>
        <div class="application_messages">
            <span>・承認待ちのため修正はできません。</span>
        </div>
    </div>
    @endif
</div>
@endsection