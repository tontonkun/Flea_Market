@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
<div class="entire">
    <div class="itemList-container">
        <h2>その他の取引</h2>
    </div>

    <div class="chat-container">
        <div class="headerSection">
            <div class="profileIcon">
                @if($partnerProfile && $partnerProfile->user_image_pass)
                <img src="{{ asset('storage/profile_images/' . $partnerProfile->user_image_pass) }}" class="profileIconImage">
                @else
                <div class="defaultProfileIcon"></div>
                @endif
            </div>
            <h2>
                @if ($chatPartner)
                「{{ $chatPartner->name }}」さんとの取引履歴
                @else
                チャット相手が不明です
                @endif
            </h2>
        </div>

        <div class="itemSection">
            <img class="itemImage" src="{{ asset('/' . $item->item_img_pass) }}" alt="{{ $item->item_name }}">
            <div class="itemInfo">
                <h2>{{ $item->item_name }}</h2>
                <h3>{{ $item->price }}円</h3>
            </div>
        </div>

        <div class="messageSection">
            @foreach ($messages as $message)
            @php
            $isMine = $message->user_id === auth()->id();
            @endphp

            <div class="message {{ $isMine ? 'my-message' : 'other-message' }}">
                <div class="message-content">
                    <div class="message-meta {{ $isMine ? 'mine' : 'partner' }}">
                        @if (!$isMine)
                        {{-- 相手：画像 → 名前 --}}
                        <img src="{{ asset('storage/profile_images/' . ($message->user->profile->user_image_pass ?? '')) }}" class="mini-profile-image" alt="プロフィール画像">
                        <strong>{{ $message->user->name }}</strong>
                        @else
                        {{-- 自分：名前 → 画像 --}}
                        <strong>{{ $message->user->name }}</strong>
                        <img src="{{ asset('storage/profile_images/' . ($message->user->profile->user_image_pass ?? '')) }}" class="mini-profile-image" alt="プロフィール画像">
                        @endif
                    </div>

                    @if ($message->image_path)
                    <img src="{{ asset('storage/' . $message->image_path) }}" class="chat-image" alt="画像">
                    @endif

                    <p>{{ $message->content }}</p>

                    @if ($isMine)
                    <!-- 編集・削除ボタンを追加 -->
                    <div class="message-actions">
                        <a href="{{ route('chat.edit', $message->id) }}" class="edit-message">編集</a>
                        <form action="{{ route('chat.delete', $message->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-message">削除</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- メッセージ送信フォーム -->
        <form action="{{ route('chat.send', $item->id) }}" method="POST" enctype="multipart/form-data" class="message-form">
            @csrf

            <textarea name="message" placeholder="取引メッセージを入力してください" required>{{ old('message') }}</textarea>

            <label for="image-upload" class="custom-file-label">画像を追加</label>
            <input type="file" name="image" id="image-upload" accept="image/*" class="custom-file-input">

            @error('message')
            <div class="error-message">{{ $message }}</div>
            @enderror

            <button class="sendMessage" type="submit"></button>
        </form>
    </div>
</div>
@endsection