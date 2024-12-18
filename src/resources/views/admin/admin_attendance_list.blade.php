@extends('layouts.admin_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_attendance_list.css') }}">
@endsection

@section('content')
<div class="admin-attendance-list_content">
    <div class="admin-attendance-list_title">
        <h2 class="admin-attendance-list_title-text">{{ $date->format('Y年m月d日') }}の勤怠</h2>
    </div>
    <div class="admin-attendance-list_header">
        <a href="{!! '/admin/attendance/list/' . ($num - 1) !!}" class="month_link">&lt;</a>
        <span class="month_text">{{ $date->format('Y/m/d') }}</span>
    </div>
    <div class="admin-attendance-list_table">
        <table>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->user_name }}</td>
                <td>{{ $attendance->clock_in_time }}</td>
                <td>{{ $attendance->clock_out_time }}</td>
                <td>{{ $attendance->rest_sum }}</td>
                <td>{{ $attendance->work_time}}</td>
                <td>
                    <a href="/admin/attendance/{{ $attendance->id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection