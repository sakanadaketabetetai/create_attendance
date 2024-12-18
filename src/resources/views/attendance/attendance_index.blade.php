@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance_index.css') }}">
@endsection

@section('content')
<div class="attendance-list_content">
    <div class="attendance-list_title">
        <h2 class="attendance-list_title-text">勤怠一覧</h2>
    </div>
    <div class="attendance-list_header">
        <a href="{!! '/attendance/list/' . ($num - 1) !!}" class="month_link">← 前月</a>
        <span class="month_text">{{ $month }}</span>
        <a href="{!! '/attendance/list/' . ($num + 1) !!}" class="month_link">翌月 →</a>
    </div>
    <div class="attendance-list_table">
        <table>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->clock_in_time }}</td>
                <td>{{ $attendance->clock_out_time }}</td>
                <td>{{ $attendance->rest_sum }}</td>
                <td>{{ $attendance->work_time }}</td>
                <td>
                    <a href="/attendance/{{ $attendance->id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection