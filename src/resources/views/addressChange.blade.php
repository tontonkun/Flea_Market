@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/changeAddress.css') }}">
@endsection

@section('mainContents')
<form class="form" action="{{ url('/changeAddress/' . $item->id) }}" method="POST">
    @csrf
    <div class="titleArea">
        <div class="title">
            住所の変更
        </div>
    </div>

    <div class="inputArea">
        <div class="inputAreaTitle">
            郵便番号
        </div>
        <!-- 郵便番号をデフォルトで表示 -->
        <input class="postal_code" type="tel" name="postal_code" pattern="\d{3}-\d{4}"
            value="{{ old('postal_code', $profile->postal_code ?? '') }}">
        @error('postal_code')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>


    <div class="inputArea">
        <div class="inputAreaTitle">
            住所
        </div>
        <!-- 住所をデフォルトで表示 -->
        <input class="address" type="text" name="address" value="{{ old('address', $profile->address ?? '') }}">
        @error('address')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="inputArea">
        <div class="inputAreaTitle">
            建物名
        </div>
        <!-- 建物名をデフォルトで表示 -->
        <input class="building_name" type="text" name="building_name"
            value="{{ old('building_name', $profile->building_name ?? '') }}">
        @error('building_name')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="buttonArea">
        <button class="updateButton" type="submit">更新する
        </button>
    </div>

</form>
@endsection