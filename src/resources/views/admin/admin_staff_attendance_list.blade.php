@extends('layouts.admin_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_staff_attendance_list.css') }}">
@endsection
@section('content')
<div class="admin-staff-attendance-list_content">
    <div class="admin-staff-attendance-list_title">
        <h2 class="admin-staff-attendance-list_title-text">{{ $user->name }}さんの勤怠</h2>
    </div>
    <div class="admin-staff-attendance-list_header">
        <a href="{!! '/admin/attendance/staff/' . $user->id . '/' . ($num - 1) !!}" class="month_link">← 前月</a>
        <span>{{ $formattedMonth }}</span>
        <a href="{!! '/admin/attendance/staff/' . $user->id . '/' . ($num + 1) !!}" class="month_link">翌月 →</a>
    </div>
    <div class="admin-staff-attendance-list_table">
        <table>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach( $attendances as $attendance)
            <tr>
                <td>{{ $attendance->date }}</td>
                <td>{{ $attendance->clock_in_time }}</td>
                <td>{{ $attendance->clock_out_time }}</td>
                <td>{{ $attendance->rest_sum }}</td>
                <td>{{ $attendance->work_time }}</td>
                <td>
                    <a href="/admin/attendance/{{ $attendance->id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="admin-staff-attendance_export">
        <form action="/admin/attendance/staff/export" method="post">
            @csrf
            <input type="hidden" name="searchDate" value="{{ $formattedMonth }}">
            <input type="hidden" name="num" value="{{ $num }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button class="admin-staff-attendance_button" type="submit">CSV出力</button>
        </form>
    </div>
</div>
@endsection