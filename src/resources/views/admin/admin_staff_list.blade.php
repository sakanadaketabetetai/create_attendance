@extends('layouts.admin_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_staff_list.css') }}">
@endsection

@section('content')
<div class="admin-staff-list_content">
    <div class="admin-staff-list_title">
        <h2 class="admin-staff-list_title-text">スタッフ一覧</h2>
    </div>
    <div class="admin-staff-list_table">
        <table>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月別勤怠</th>
            </tr>
            @foreach( $users as $user )
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="/admin/attendance/staff/{{ $user->id }}/{{ $num = 0 }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection