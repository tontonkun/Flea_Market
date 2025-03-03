@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/topPage.css') }}">
@endsection

@section('content')
    @csrf
    <div class="titleArea">
        <div class="title">
            トップページ作成
        </div>
    </div>

@endsection