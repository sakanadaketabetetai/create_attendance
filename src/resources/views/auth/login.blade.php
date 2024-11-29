@extends('layouts.app')

@section('css')

@endsection

@section('content')
<div class="login-content">
    <div class="login-content_title">
        <h2>ログイン</h2>
    </div>
    <div class="login-content_form">
        <form action="" method="post">
            @csrf
            <div class="login-content_form-input">
                <label for="email-input">メールアドレス</label>
                <input type="text" name="email" id="email">
            </div>
            <div class="login-content_form-input">
                <label for="password-input">パスワード</label>
                <input type="password" name="password" id="password-input">
            </div>
            <div class="login-content_form-button">
                <button class="login-content_form-button-submit" type="submit">ログイン</button>
            </div>
        </form>
        <div class="login-content_link">
            <a href="/register">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection