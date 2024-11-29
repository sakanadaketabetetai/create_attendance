<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Document</title>
    @yield('css')
</head>
<body>
    <header class="app-header">
        <div class="app-header_inner">
            <div class="app-header_img">
                <img src="" alt="">
            </div>
            <div class="app-header_nav">
                @if(Auth::check() )
                <div class="app-header-nav_link">
                    <a href="/admin/attendance/list/{{ $num = 0 }}" class="app-header_nav-link">勤怠一覧</a>
                </div>
                <div class="app-header-nav_link">
                    <a href="/admin/attendance/staff/list" class="app-header_nav-link">スタッフ一覧</a>
                </div>
                <div class="app-header-nav_link">
                    <a href="" class="app-header_nav-link">申請一覧</a>
                </div>
                <div class="app-header-nav_link">
                    <form action="/logout" method="post">
                        @csrf
                        <button class="app-header_nav-button" type="submit">ログアウト</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </header>
    <main>
        <div class="app-content">
            @yield('content')
        </div>

    </main>
</body>
</html>