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
            <div class="profileIcon" id="profileIconArea">
                <!-- 画像がアップロードされている場合 -->
                @if($profile && $profile->user_image_pass)
                    <img src="{{ asset('storage/profile_images/' . $profile->user_image_pass) }}" alt="Profile Icon"
                        class="profileIconImage" id="profileImagePreview" style="display: block;">
                @else
                    <!-- 画像がない場合 -->
                    <div class="defaultProfileIcon" id="defaultProfileText"></div>
                @endif
            </div>

            <div class="uploadButtonArea">
                <label for="user_image_pass" class="customUploadButton">画像を選択する</label>
                <input type="file" id="user_image_pass" name="user_image_pass" class="fileInput"
                    onchange="previewProfileImage(event)">
                @error('user_image_pass')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="inputAreaTitle">
            ユーザー名
        </div>
        <div class="inputArea">
            <input class="name" type="text" name="user_name" value="{{ $profile->user_name ?? '' }}">
            @error('user_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            郵便番号
        </div>
        <div class="inputArea">
            <input class="postal_code" type="tel" name="postal_code" pattern="\d{3}-\d{4}"
                value="{{ $profile->post_code ?? '' }}">
            @error('postal_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            住所
        </div>
        <div class="inputArea">
            <input class="address" type="text" name="address" value="{{ $profile->address ?? '' }}">
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="inputAreaTitle">
            建物名
        </div>
        <div class="inputArea">
            <input class="building_name" type="text" name="building_name" value="{{ $profile->building_name ?? '' }}">
            @error('building_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="buttonArea">
            <button class="updateButton" type="submit">更新する</button>
        </div>

    </form>

    <script>
        function previewProfileImage(event) {
            const file = event.target.files[0]; // 選択したファイルを取得

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imagePreview = document.getElementById('profileImagePreview');
                    const defaultText = document.getElementById('defaultProfileText');

                    // プレビュー画像が存在しない場合、動的に作成
                    if (!imagePreview) {
                        // <img>要素を動的に作成
                        const newImagePreview = document.createElement('img');
                        newImagePreview.id = 'profileImagePreview';
                        newImagePreview.style.maxWidth = '100%';
                        newImagePreview.style.maxHeight = '300px';
                        document.getElementById('profileIconArea').appendChild(newImagePreview);
                    }

                    // プレビュー画像を設定
                    const updatedImagePreview = document.getElementById('profileImagePreview');
                    updatedImagePreview.src = e.target.result;
                    updatedImagePreview.style.display = 'block'; // プレビュー画像を表示

                    // 「画像がありません」テキストを非表示
                    if (defaultText) {
                        defaultText.style.display = 'none';
                    }
                };

                reader.onerror = function () {
                    console.error("画像の読み込みに失敗しました");
                };

                reader.readAsDataURL(file);
            }
        }
    </script>

@endsection