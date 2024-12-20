@extends('layouts.admin_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/approval_correct.css') }}">
@endsection

@section('content')
<div class="approve-correct_content">
    <div class="approve-correct_title">
        <h2>勤怠詳細</h2>
    </div>
    <div class="approve-correct_form">
        <table class="approve-correct_form-table">
            <tr>
                <th>名前</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    {{ $approval_request->formattedYear }}
                </td>
                <td></td>
                <td>
                    {{ $approval_request->formattedDate }}
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    {{ $approval_request->clock_in_time }}
                </td>
                <td>～</td>
                <td>
                    {{ $approval_request->clock_out_time }}
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
                    <textarea name="late_reason" id="">{{ $approval_request->late_reason }}</textarea>
                </td>
            </tr>
        </table>
        @if($approval_request->approval_status == 'pending')
        <div class="approve-correct_button">
            <form action="/admin/stamp_correction_request/approve/{{ $approval_request->id }}" method="post">
                @csrf
                <input type="hidden" name="approval_request_id" value="{{ $approval_request->id }}">
                <button class="approve-correct_button-submit" type="submit">承認</button>
            </form>
        </div>
        @endif            
        @if($approval_request->approval_status == "approval")
            <div class="admin-attendance-detail_approval">
                <span class="admin-attendance-detail_button-submit">承認済み</span>
            </div>
        @endif
    </div>
</div>
@endsection