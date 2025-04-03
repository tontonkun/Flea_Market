@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

    <form class="form" action="/setUpProfiles" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="titleArea">
            <div class="title">
                プロフィール設定
            </div>
        </div>

        <div class="iconSettingArea">
            <div class="profileIcon">
                @if($profile && $profile->profile_image)
                    <!-- ユーザーが画像をアップロードした場合 -->
                    <img src="{{ asset('storage/profile_images/' . $profile->profile_image) }}" alt="Profile Icon"
                        class="profileIconImage">
                @else
                    <!-- 画像が登録されていない場合 -->
                    <div class="defaultProfileIcon" id="profileImagePreview"></div>
                @endif
            </div>
            <div class="uploadButtonArea">
                <label for="profile_image" class="customUploadButton">画像を選択する</label>
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
            <!-- ユーザー名をデフォルトで表示 -->
            <input class="name" type="text" name="user_name" value="{{ old('name', $profile->user_name ?? '') }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            郵便番号
        </div>
        <div class="inputArea">
            <!-- 郵便番号をデフォルトで表示 -->
            <input class="postal_code" type="tel" name="postal_code" pattern="\d{3}-\d{4}"
                value="{{ old('postal_code', $profile->postal_code ?? '') }}">
            @error('postal_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            住所
        </div>
        <div class="inputArea">
            <!-- 住所をデフォルトで表示 -->
            <input class="address" type="text" name="address" value="{{ old('address', $profile->address ?? '') }}">
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            建物名
        </div>
        <div class="inputArea">
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

@section('js')
    <script>
        // 画像選択時にプレビューを表示
        document.getElementById('profile_image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                // プレビュー画像を表示
                const profileImagePreview = document.getElementById('profileImagePreview');
                profileImagePreview.src = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection