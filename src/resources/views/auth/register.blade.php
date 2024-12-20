@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css')}}">
@endsection

@section('content')
<div class="register-content">
    <div class="register-content_title">
        <h2>会員登録</h2>
    </div>
    <div class="register-content_form">
        <form action="" method="post">
            @csrf
            <div class="register-content_form-input">
                <label for="name-input">名前</label>
                <input type="text" name="name" id="name-input">
            </div>
            @error('name')
            <div class="register-content_error">
                <p class="register-content_error-message">{{ $message }}</p>
            </div>
            @enderror
            <div class="register-content_form-input">
                <label for="email-input">メールアドレス</label>
                <input type="text" name="email" id="email-input">
            </div>
            @error('email')
            <div class="register-content_error">
                <p class="register-content_error-message">{{ $message }}</p>
            </div>
            @enderror
            <div class="register-content_form-input">
                <label for="password-input">パスワード</label>
                <input type="password" name="password" id="password-input">
            </div>
            @error('password')
            <div class="register-content_error">
                <p class="register-content_error-message">{{ $message }}</p>
            </div>
            @enderror
            <div class="register-content_form-input">
                <label for="password-confirm-input">パスワード確認</label>
                <input type="password" name="password_confirmation" id="password-confirm-input">
            </div>
            <div class="register-content_form-button">
                <button class="register-content_form-button-submit" type="submit">登録する</button>
            </div>
        </form>
        <div class="register-content_link">
            <a href="/login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection