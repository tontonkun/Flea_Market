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
            <h2>{{ $item->item_name }}</h2>
        </div>

        <div class="messages">
            @foreach ($messages as $message)
                @php
                    $isMine = $message->user_id === auth()->id();
                @endphp

                <div class="message {{ $isMine ? 'my-message' : 'other-message' }}">
                    <div class="message-content">
                        <div class="message-meta">
                            <strong>{{ $message->user->name }}</strong>
                            <span class="timestamp">{{ $message->created_at->diffForHumans() }}</span>
                        </div>

                        @if ($message->image_path)
                            <img src="{{ asset('storage/' . $message->image_path) }}" class="chat-image" alt="画像">
                        @endif

                        <p>{{ $message->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- メッセージ送信フォーム -->
        <form action="{{ route('chat.send', $item->id) }}" method="POST" enctype="multipart/form-data" class="message-form">
            @csrf
            <textarea name="message" placeholder="メッセージを入力..." required>{{ old('message') }}</textarea>

            <input type="file" name="image" accept="image/*">

            @error('message')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <button class="sendMessage" type="submit">送信</button>
        </form>
    </div>
</div>
@endsection
