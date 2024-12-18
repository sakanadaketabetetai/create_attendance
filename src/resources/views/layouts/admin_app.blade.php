<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin_app.css') }}">
    <title>Document</title>
    @yield('css')
</head>
<body>
    <header class="admin-app-header">
        <div class="admin-app-header_inner">
            <div class="admin-app-header_img">
                <img src="" alt="">
            </div>
            <div class="admin-app-header_nav">
                @if(Auth::check() )
                <div class="admin-app-header-nav_link">
                    <a href="/admin/attendance/list/{{ $num = 0 }}" class="admin-app-header_nav-link">勤怠一覧</a>
                </div>
                <div class="admin-app-header-nav_link">
                    <a href="/admin/attendance/staff/list" class="admin-app-header_nav-link">スタッフ一覧</a>
                </div>
                <div class="admin-app-header-nav_link">
                <form action="/admin/stamp_correction_request/list" method="post">
                        @csrf 
                        <input type="hidden" name="admin-approval_status" value="pending">
                        <button class="admin-app-header-nav_link-button" type="submit">申請一覧</button>
                    </form>
                </div>
                <div class="admin-app-header-nav_link">
                    <form action="/logout" method="post">
                        @csrf
                        <button class="admin-app-header-nav_link-button" type="submit">ログアウト</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </header>
    <main>
        <div class="admin-app-content">
            @yield('content')
        </div>

    </main>
</body>
</html>