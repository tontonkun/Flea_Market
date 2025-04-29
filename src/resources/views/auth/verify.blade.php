@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}"> 
@endsection

@section('content')
    <form class="form" method="POST" action="{{ route('verification.resend') }}">
        @csrf

        <div class="titleArea">
            <div class="title">
                メール認証
            </div>
        </div>

        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('認証用のメールが送信されました') }}
            </div>
        @endif

        <div class="inputAreaTitle">
            {{ __('ご登録されたメールアドレスに、認証用メールを送信します') }}
        </div>
        <div class="inputAreaTitle">
            {{ __('以下ボタンを押してください') }}
        </div>

        <div class="buttonArea">
            <button type="submit" class="loginButton">{{ __('メールを送信') }}</button>
        </div>
    </form>
@endsection
