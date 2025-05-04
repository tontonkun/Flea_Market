@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
<link rel="stylesheet" href="{{ asset('css/itemSectionInChat.css') }}">
<link rel="stylesheet" href="{{ asset('css/modalInChat.css') }}">
@endsection

@section('mainContents')
<div class="entire">
    <!-- 取引中の商品リスト -->
    <div class="itemList-container">
        <h2>その他の取引</h2>
        <div class="item-list">
            @forelse($otherItems as $otherItem)
            <div class="item-card">
                <a href="{{ route('chat.show', $otherItem->id) }}">
                    <div class="item-name">{{ $otherItem->item_name }}</div>
                </a>
            </div>
            @empty
            <p>なし</p>
            @endforelse
        </div>
    </div>

    <!-- チャットエリア -->
    <div class="chat-container">
        <div class="headerSection">
            <div class="partnerInfo">
                <div class="profileIcon">
                    @if($partnerProfile && $partnerProfile->user_image_pass)
                    <img src="{{ asset('storage/profile_images/' . $partnerProfile->user_image_pass) }}" class="profileIconImage">
                    @else
                    <div class="defaultProfileIcon"></div>
                    @endif
                </div>
                <h2>
                    @if ($chatPartner && $chatPartner->profile)
                    「{{ $chatPartner->profile->user_name }}」さんとの取引履歴
                    @elseif ($chatPartner)
                    「{{ $chatPartner->name }}」さんとの取引履歴
                    @else
                    取引相手情報が取得できませんでした。
                    @endif
                </h2>
            </div>

            <!-- 取引完了ボタン -->
            @if ($item->in_trade && $myId === $buyerId)
            <button id="evaluationBtn" class="evaluationBtn">
                取引を完了する
            </button>
            @endif
        </div>

        <!-- 取引完了モーダル -->
        <div id="transactionModal" class="transaction-modal" style="display: none;">
            <div class="transaction-modal-content">
                <form action="{{ route('chat.evaluation', $item->id) }}" method="POST">
                    @csrf
                    <div class="topPart">取引が完了しました。</div>
                    <div class="middlePart">
                        <p>今回の取引相手はどうでしたか？</p>
                        <div class="rating" id="ratingStars">
                            <span data-value="1">★</span>
                            <span data-value="2">★</span>
                            <span data-value="3">★</span>
                            <span data-value="4">★</span>
                            <span data-value="5">★</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="">
                    </div>
                    <div class="lowerPart">
                        <button type="submit" class="submit-rating">送信する</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 商品情報セクション -->
        <div class="itemSection">
            <img class="itemImage" src="{{ asset('/' . $item->item_img_pass) }}" alt="{{ $item->item_name }}">
            <div class="itemInfo">
                <h2>{{ $item->item_name }}</h2>
                <h3>{{ number_format($item->price) }}円</h3>
            </div>
        </div>

        <!-- メッセージ表示エリア -->
        <div class="messageSection">
            @foreach ($messages as $message)
            @php
            $isMine = $message->user_id === auth()->id();
            @endphp

            <div class="message {{ $isMine ? 'my-message' : 'other-message' }}">
                <div class="message-content">
                    <div class="message-meta {{ $isMine ? 'mine' : 'partner' }}">
                        @if (!$isMine)
                        <img src="{{ asset('storage/profile_images/' . ($message->user->profile->user_image_pass ?? '')) }}" class="mini-profile-image" alt="プロフィール画像">
                        <strong>{{ $message->user->name }}</strong>
                        @else
                        <strong>{{ $message->user->name }}</strong>
                        <img src="{{ asset('storage/profile_images/' . ($message->user->profile->user_image_pass ?? '')) }}" class="mini-profile-image" alt="プロフィール画像">
                        @endif
                    </div>

                    @if ($message->image_path)
                    <img src="{{ asset('storage/' . $message->image_path) }}" class="chat-image" alt="画像">
                    @endif

                    <p class="message-text">{{ $message->message }}</p>

                    @if ($isMine)
                    <!-- 編集・削除ボタン -->
                    <div class="message-actions">
                        <button class="edit-message-btn" data-message-id="{{ $message->id }}" data-message-content="{{ $message->message }}">編集</button>
                        <form action="{{ route('chat.delete', $message->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-message-btn">削除</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- メッセージ編集モーダル -->
        <div id="editMessageModal" class="edit-message-modal" style="display: none;">
            <div class="edit-message-modal-content">
                <form id="editMessageForm" method="POST">
                    @csrf
                    @method('PUT')
                    <textarea name="edit_message" id="editMessageTextarea"></textarea>
                    <div class="buttonArea">
                        <button type="submit">更新</button>
                        <button type="button" id="closeModalBtn">閉じる</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- メッセージ送信フォーム -->
        <form action="{{ route('chat.send', $item->id) }}" method="POST" enctype="multipart/form-data" class="message-form">
            @csrf

            <!-- 画像プレビュー -->
            <div id="imagePreviewWrapper" style="display: none;">
                <img id="imagePreview" src="#" alt="画像プレビュー" style="max-width: 100%; max-height: 200px;">
            </div>

            <!-- テキストエリア + 画像選択 + 送信ボタン -->
            <div class="message-input-row">
                <textarea name="message" id="messageTextarea" placeholder="取引メッセージを記入してください"></textarea>


                <div class="chatButtons">
                    <!-- 画像選択 -->
                    <label for="image-upload" class="custom-file-label">画像を追加</label>
                    <input type="file" name="image" id="image-upload" accept="image/*" class="custom-file-input" style="display: none;">

                    <!-- 送信ボタン -->
                    <button class="sendMessage" type="submit"></button>
                </div>
        </form>
    </div>

    <div id="chatData"
        data-in-trade="{{ $item->in_trade ? '1' : '0' }}"
        data-my-id="{{ $myId }}"
        data-seller-id="{{ $item->seller_id }}"
        data-buyer-id="{{ $buyerId }}"
        data-has-rated="{{ isset($hasRated) && $hasRated ? '1' : '0' }}"
        data-has-partner-rated="{{ isset($hasPartnerRated) && $hasPartnerRated ? '1' : '0' }}">
    </div>

</div>

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- チャットメッセージの自動保存・復元 ---
        const textarea = document.querySelector('.message-form textarea[name="message"]');
        console.log('取得したtextarea:', textarea);
        const storageKey = 'chatMessageDraft_{{ $item->id }}';

        if (textarea) {
            // 復元処理
            const savedMessage = localStorage.getItem(storageKey);
            if (savedMessage && savedMessage.trim() !== '') {
                textarea.value = savedMessage;
                console.log('復元:', savedMessage);
            }

            // 入力時に保存
            textarea.addEventListener('input', function() {
                localStorage.setItem(storageKey, this.value);
                console.log('保存:', this.value);
            });
        } else {
            console.warn('textarea[name="message"] が見つかりません');
        }

        // 送信時にlocalStorage削除
        document.querySelector('.message-form')?.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });


        // --- チャット完了モーダル表示切替 ---
        document.getElementById('evaluationBtn')?.addEventListener('click', function() {
            document.getElementById('transactionModal').style.display = 'flex';
        });

        document.getElementById('closeModalBtn')?.addEventListener('click', function() {
            document.getElementById('transactionModal').style.display = 'none';
            document.getElementById('editMessageModal').style.display = 'none';
        });

        // --- 星評価のクリック処理 ---
        const stars = document.querySelectorAll('#ratingStars span');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                ratingInput.value = value;

                stars.forEach(s => {
                    const starValue = parseInt(s.getAttribute('data-value'));
                    s.classList.toggle('selected', starValue <= value);
                });
            });
        });

        // --- 自動モーダル表示条件処理（評価関連） ---
        const chatDataElement = document.getElementById('chatData');
        if (chatDataElement) {
            const inTrade = chatDataElement.dataset.inTrade === '1';
            const myId = parseInt(chatDataElement.dataset.myId);
            const sellerId = parseInt(chatDataElement.dataset.sellerId);
            const buyerId = parseInt(chatDataElement.dataset.buyerId);
            const hasRated = chatDataElement.dataset.hasRated === '1';
            const hasPartnerRated = chatDataElement.dataset.hasPartnerRated === '1';

            // 出品者が評価していなくて、購入者は評価済みなら表示
            if (myId === sellerId && hasRated && hasPartnerRated) {
                console.log("出品者が評価済みで、購入者が評価済み");
                document.getElementById('transactionModal').style.display = 'flex';
            }

            // 購入者側で未評価なら表示
            if (!inTrade && myId === buyerId && !hasRated) {
                document.getElementById('transactionModal').style.display = 'flex';
            }
        }

        // --- メッセージ編集モーダル表示処理 ---
        document.querySelectorAll('.edit-message-btn').forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-message-id');
                const messageContent = this.getAttribute('data-message-content');

                document.getElementById('editMessageTextarea').value = messageContent;
                document.getElementById('editMessageForm').action = `/chat/${messageId}/edit`;

                document.getElementById('editError')?.remove();
                document.getElementById('editMessageModal').style.display = 'flex';
            });
        });

        // --- 編集バリデーション ---
        document.getElementById('editMessageForm')?.addEventListener('submit', function(event) {
            const textarea = document.getElementById('editMessageTextarea');
            const content = textarea.value.trim();

            document.getElementById('editError')?.remove();

            if (content === '') {
                event.preventDefault();
                showValidationError('本文を入力してください');
            } else if (content.length > 400) {
                event.preventDefault();
                showValidationError('本文は400文字以内で入力してください。');
            }

            function showValidationError(message) {
                const errorElem = document.createElement('p');
                errorElem.id = 'editError';
                errorElem.style.color = 'red';
                errorElem.style.marginTop = '8px';
                errorElem.textContent = message;
                textarea.parentNode.insertBefore(errorElem, textarea.nextSibling);
            }
        });

        // --- 画像プレビュー処理 ---
        document.getElementById('image-upload')?.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewWrapper = document.getElementById('imagePreviewWrapper');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewWrapper.style.display = 'block';
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreviewWrapper.style.display = 'none';
                imagePreview.style.display = 'none';
            }
        });
    });
</script>

@endsection

@endsection