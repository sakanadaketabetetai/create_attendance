@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_login.css') }}">
@endsection

@section('content')
<div class="admin-login-content">
    <div class="admin-login-content_title">
        <h2>管理者ログイン</h2>
    </div>
    <div class="admin-login-content_form">
        <form action="/admin/login" method="post">
            @csrf
            <div class="admin-login-content_form-input">
                <label for="email-input">メールアドレス</label>
                <input type="text" name="email" id="email">
            </div>
            @error('email')
            <div class="admin-login-content_error">
                <p class="admin-login-content_error-message">
                    {{ $message }}
                </p>
            </div>
            @enderror
            <div class="admin-login-content_form-input">
                <label for="password-input">パスワード</label>
                <input type="password" name="password" id="password-input">
            </div>
            @error('password')
            <div class="admin-login-content_error">
                <p class="admin-login-content_error-message">
                    {{ $message }}
                </p>
            </div>
            @enderror
            <div class="admin-login-content_form-button">
                <button class="admin-login-content_form-button-submit" type="submit">管理者ログインする</button>
            </div>
        </form>
    </div>
</div>
@endsection