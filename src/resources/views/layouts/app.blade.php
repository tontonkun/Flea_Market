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

        @auth
            <div class="headerlink">
                @yield('ButtonForLogin/Logout')

                <form action="/mypage" method="GET">
                    @csrf
                    <button class="mypage">マイページ</button>
                </form>

                <form action="/sell" method="GET">
                    @csrf
                    <button class="sell">出品</button>
                </form>
            </div>
        @endauth

    </div>

    @yield('content')

</body>

</html>