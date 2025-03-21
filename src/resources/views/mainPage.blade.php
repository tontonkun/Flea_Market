@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
    <div class="displaySelection">
        <button id="recommends" class="recommends">おすすめ</button>
        <button id="myList" class="myList">マイリスト</button>
    </div>

@endsection