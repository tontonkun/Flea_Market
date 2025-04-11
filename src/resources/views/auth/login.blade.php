@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> <!-- ログインページ専用CSS -->
@endsection

@section('content')

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form class="form" action="/login" method="post">
        @csrf
        <div class="titleArea">
            <div class="title">
                ログイン
            </div>
        </div>

        <div class="inputAreaTitle">
            メールアドレス
        </div>
        <div class="inputArea">
            <input class="mail" type="email" name="email" value="{{ old('email') }}" />
        </div>
        <div class="form__error">
            @error('email')
                {{ $message }}
            @enderror
        </div>

        <div class="inputAreaTitle">
            パスワード
        </div>
        <div class="inputArea">
            <input class="password" type="password" name="password" />
        </div>
        <div class="form__error">
            @error('password')
                {{ $message }}
            @enderror
        </div>

        <div class="buttonArea">
            <button class="loginButton" type="submit">ログインする</button>
        </div>

        <div class="forRegister">
            <a href="register" class="registerLink">
                会員登録はこちら
            </a>
        </div>
    </form>
@endsection