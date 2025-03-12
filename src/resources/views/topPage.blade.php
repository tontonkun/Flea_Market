@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/topPage.css') }}">
@endsection

{{-- フラッシュメッセージの表示 --}}
@if(session('success'))
    <div class="announce postingItem">
        {{ session('success') }}
    </div>
@endif

@section('content')
    @csrf
    <div class="titleArea">
        <div class="title">
            トップページ作成
        </div>
    </div>

@endsection