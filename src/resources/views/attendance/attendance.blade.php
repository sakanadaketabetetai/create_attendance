@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-content">
    <div class="attendance-content_status">
        <p class="attendance-content_status-text">{{ $attendance_status }}</p>
    </div>
    <div class="attendance-content_date">
        <h4 class="attendance-content_date-text">{{ $date }}</h4>
    </div>
    <div class="attendance-content_time">
        <h4 class="attendance-content_time-text">{{ $time }}</h4>
    </div>
    @if($attendance_status == "勤務外")
    <div class="attendance-content_button">
        <form action="/attendance/clock_in" method="get">
            @csrf
            <input type="hidden" name="attendance_id" value="$a">
            <button type="submit" class="attendance-content_button-submit">出勤</button>
        </form>
    </div>
    @endif
    @if($attendance_status == "出勤中")
    <div class="attendance-content_buttons">
        <div class="attendance-content_button">
            <form action="/attendance/clock_out" method="get">
                @csrf
                <button type="submit" class="attendance-content_button-submit">退勤</button>
            </form>
        </div>
        <div class="attendance-content_button">
            <form action="/attendance/rest_start" method="get">
                @csrf
                <button type="submit" class="attendance-content_button-submit-rest">休憩入</button>
            </form>
        </div>
    </div>
    @endif
    @if($attendance_status == "休憩中")
    <div class="attendance-content_button">
        <form action="/attendance/rest_end" method="get">
            @csrf
            <button type="submit" class="attendance-content_button-submit-rest">休憩戻</button>
        </form>
    </div>
    @endif
    @if($attendance_status == "退勤済")
    <div class="attendance-content_button">
        <p class="attendance-content_message">お疲れ様でした。</p>
    </div>
    @endif

</div>
@endsection