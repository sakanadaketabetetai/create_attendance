@extends('layouts.app')

@section('css')

@endsection

@section('content')
<div class="approval_content">
    <div class="approval_title">
        <h2>申請一覧</h2>
    </div>
    <div class="approval_filters-button">
        <form action="" method="get">
            @csrf
            <div class="approval_button">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="approval_status" value="pending">
                @if($approval_status == "pending")
                    <button class="approval_button-submit-select" type="submit">承認待ち</button>
                @else
                    <button class="approval_button-submit" type="submit">承認待ち</button>
                @endif
            <div class="approval_button">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="approval_status" value="approval">
                @if($approval_status == "pending")
                    <button class="approval_button-submit-select" type="submit">承認済み</button>
                @else
                    <button class="approval_button-submit" type="submit">承認済み</button>
                @endif
            </div>
        </form>
    </div>
    <div class="approval_table">
        <table>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
            @foreach ( $approval_requests as $approval_request )
            <tr>
                <td>{{ $approval_request->approval_status }}</td>
                <td>{{ $approval_request->user_name }}</td>
                <td>{{ $approval_request->attendance_date }}</td>
                <td>{{ $approval_request->late_reason }}</td>
                <td>{{ $approval_request->approval_date }}</td>
                <td>{{ $approval_request->approval_ }}</td>
                <td>{{ $approval_request->approval_status }}</td>
            </tr>
            @endforeach
        </table>
    </div>

</div>
@endsection