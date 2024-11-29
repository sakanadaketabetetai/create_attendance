@extends('layouts.admin_app')

@section('css')

@endsection
@section('content')
<div class="admin-staff-attendance-list_content">
    <div class="admin-staff-attendance-list_title">
        <h2>{{ $user->name }}さんの勤怠</h2>
    </div>
    <div class="admin-staff-attendance-list_header">
        <a href="{!! '/admin/attendance/staff/' . $user->id . '/' . ($num - 1) !!}" class="month_link">&lt;</a>
        <span>{{ $formattedMonth }}</span>
        <a href="{!! '/admin/attendance/staff/' . $user->id . '/' . ($num + 1) !!}" class="month_link">&gt;</a>
    </div>
    <div class="admin-staff-attendance-list_table">
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
            <td>{{ $attendance->rest_time }}</td>
            <td>{{ $attendance->work_time }}</td>
            <td>
                <a href="">詳細</a>
            </td>
        </tr>
        @endforeach
    </div>
</div>
@endsection