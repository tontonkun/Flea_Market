<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <div class="header">
        <div class="headerlogo">
            <img src="/img/logo.svg" alt="coachtech" width="220" height="50">
        </div>

        <div class="headerLinks">
            @auth
                <!-- ログイン中 -->
                <form action="/logout" method="POST">
                    @csrf
                    <button class="logout">ログアウト</button>
                </form>
            @endauth

            @guest
                <!-- ログアウト中 -->
                <form action="/login" method="GET">
                    @csrf
                    <button class="login">ログイン</button>
                </form>
            @endguest

            @auth
                <form action="/myPage" method="GET">
                    @csrf
                    <button class="myPage">マイページ</button>
                </form>

                <form action="/sell" method="GET">
                    @csrf
                    <button class="sell">出品</button>
                </form>
            @endauth
        </div>
    </div>
    @yield('content')

</body>

</html>