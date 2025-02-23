@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/myPage.css') }}">
@endsection

@section('content')


    <form class="form" action="setUpProfiles" method="POST">
        @csrf
        <div class="titleArea">
            <div class="title">
                プロフィール設定
            </div>
        </div>

        <div class="profileIconArea">
            <!-- 画像が登録されていない場合はデフォルトの丸型アイコンを表示 -->
            <div class="profileIcon">
                @if(Auth::user()->profile_image) <!-- ユーザーが画像をアップロードした場合 -->
                    <img src="{{ asset('storage/profile_images/' . Auth::user()->profile_image) }}" alt="Profile Icon"
                        class="profileIconImage">
                @else <!-- 画像が登録されていない場合 -->
                    <div class="defaultProfileIcon"></div>
                @endif
            </div>
            <div class="uploadButtonArea">
                <label for="profile_image" class="uploadButton">アイコン画像を選択</label>
                <input type="file" id="profile_image" name="profile_image" class="fileInput">
                @error('profile_image')
                    <div class="error">{{ $message }}</div>
                @enderror
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
            郵便番号
        </div>
        <div class="inputArea">
            <input class="postal_code" type="tel" name="postal_code" pattern="\d{3}-\d{4}" value="{{ old('postal_code') }}">
            @error('postal_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            住所
        </div>
        <div class="inputArea">
            <input class="address" type="text" name="address">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            建物名
        </div>
        <div class="inputArea">
            <input class="building_name" type="text" name="building_name">
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