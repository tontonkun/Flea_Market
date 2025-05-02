<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    {{-- フラッシュメッセージの表示 --}}
    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-danger">
        {{ session('error') }}
    </div>
    @endif

    {{--コメント送信エラー用--}}
    @error('comment')
    <div class="alert-danger">{{ $message }}</div>
    @enderror

    {{--チャットメッセージ送信エラー用--}}
    @error('message')
    <div class="alert-danger">{{ $message }}</div>
    @enderror

    @if ($errors->has('image'))
    <div class="alert-danger">{{ $errors->first('image') }}</div>
    @endif

    <div class="header">
        <div class="headerlogo">
            <img src="/img/logo.svg" alt="coachtech" width="220" height="50">
        </div>

        @unless(request()->is('login') || request()->is('register') || (auth()->check() && !auth()->user()->hasVerifiedEmail()))
        <div class="searchBox">
            <form action="/" method="GET">
                <input type="text" name="query" value="{{ request()->query('query') }}" placeholder="何をお探しですか？"
                    class="searchInput">
                <button type="submit" class="searchButton">検索</button>
            </form>
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
        @endunless
    </div>
    @yield('mainContents')

    <script src="{{ asset('js/app.js') }}"></script>

    @yield('js')
</body>

</html>