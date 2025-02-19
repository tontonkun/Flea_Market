@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}"> <!-- ログインページ専用CSS -->
@endsection

@section('content')


<form class="form" action="register" method="POST">
    @csrf
    <div class="titleArea">
        <div class="title">
            会員登録
        </div>
    </div>

    <div class="inputAreaTitle">
        ユーザー名
    </div>
    <div class="inputArea">
        <input class="name" type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="inputAreaTitle">
        メールアドレス
    </div>
    <div class="inputArea">
        <input class="mail" type="email" name="email" value="{{ old('email') }}">
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="inputAreaTitle">
        パスワード
    </div>
    <div class="inputArea">
        <input class="password" type="password" name="password">
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="inputAreaTitle">
        確認用パスワード
    </div>
    <div class="inputArea">
        <input class="password" type="password" name="password_confirmation">
    </div>

    <div class="buttonArea">
        <button class="registerButton" type="submit">登録する
        </button>
    </div>

    <div class="annonceForMenbers">
        <a href="login" class="loginLink">
            ログインはこちら
        </a>
    </div>
</form>
@endsection