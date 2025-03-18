@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/postingPage.css') }}">
@endsection

@section('content')
    <form class="form" action="/postItems" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mainTitleArea">
            商品の出品
        </div>

        <div class="subTitle">
            商品画像
        </div>
        <div class="imageSettingArea">
            <div class="imageArea" id="imageArea">
                <!-- 画像が選択されていない場合のデフォルトの表示 -->
                <div class="defaultDisplay">
                    <div class="uploadButtonArea">
                        <input type="file" id="product_image" name="product_image" class="fileInput"
                            onchange="previewImage(event)">
                        <label for="product_image">画像を選択する</label>
                    </div>
                </div>

                <!-- 画像プレビューと画像変更ボタン -->
                <div id="imagePreviewContainer" style="display: none;">
                    <img id="imagePreview" style="max-width: 100%; max-height: 300px;">
                    <button type="button" id="changeImageButton" class="changeImageButton"
                        onclick="resetImage()">画像を変更</button>
                </div>
            </div>
        </div>

        <div class="sectionTitle">
            商品の詳細
        </div>

        <div class="subTitle">
            カテゴリー
        </div>
        <div class="categorySelectionArea">
            <input type="checkbox" id="fashion" name="selected_category[]" value="ファッション" class="categoryButton">
            <label for="fashion" class="categoryLabel">ファッション</label>

            <input type="checkbox" id="electronics" name="selected_category[]" value="家電" class="categoryButton">
            <label for="electronics" class="categoryLabel">家電</label>

            <input type="checkbox" id="interior" name="selected_category[]" value="インテリア" class="categoryButton">
            <label for="interior" class="categoryLabel">インテリア</label>

            <input type="checkbox" id="womens" name="selected_category[]" value="レディース" class="categoryButton">
            <label for="womens" class="categoryLabel">レディース</label>

            <input type="checkbox" id="mens" name="selected_category[]" value="メンズ" class="categoryButton">
            <label for="mens" class="categoryLabel">メンズ</label>

            <input type="checkbox" id="cosmetics" name="selected_category[]" value="コスメ" class="categoryButton">
            <label for="cosmetics" class="categoryLabel">コスメ</label>

            <input type="checkbox" id="books" name="selected_category[]" value="本" class="categoryButton">
            <label for="books" class="categoryLabel">本</label>

            <input type="checkbox" id="games" name="selected_category[]" value="ゲーム" class="categoryButton">
            <label for="games" class="categoryLabel">ゲーム</label>

            <input type="checkbox" id="sports" name="selected_category[]" value="スポーツ" class="categoryButton">
            <label for="sports" class="categoryLabel">スポーツ</label>

            <input type="checkbox" id="kitchen" name="selected_category[]" value="キッチン" class="categoryButton">
            <label for="kitchen" class="categoryLabel">キッチン</label>

            <input type="checkbox" id="handmade" name="selected_category[]" value="ハンドメイド" class="categoryButton">
            <label for="handmade" class="categoryLabel">ハンドメイド</label>

            <input type="checkbox" id="accessories" name="selected_category[]" value="アクセサリー" class="categoryButton">
            <label for="accessories" class="categoryLabel">アクセサリー</label>

            <input type="checkbox" id="toys" name="selected_category[]" value="おもちゃ" class="categoryButton">
            <label for="toys" class="categoryLabel">おもちゃ</label>

            <input type="checkbox" id="baby_kids" name="selected_category[]" value="ベビー・キッズ" class="categoryButton">
            <label for="baby_kids" class="categoryLabel">ベビー・キッズ</label>
        </div>

        <div class="subTitle">
            商品の状態
        </div>
        <div class="inputArea">
            <select class="item_state" name="condition_id">
                <option value="">選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->condition }}
                    </option>
                @endforeach
            </select>
            @error('condition_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="sectionTitle">
            商品名と説明
        </div>

        <div class="subTitle">
            商品名
        </div>
        <div class="inputArea">
            <input class="item_name" type="text" name="item_name" value="{{ old('item_name') }}">
            @error('item_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="subTitle">
            ブランド名
        </div>
        <div class="inputArea">
            <input class="brand_name" type="text" name="brand_name" value="{{ old('brand_name') }}">
            @error('brand_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="subTitle">
            商品の説明
        </div>
        <div class="inputArea">
            <input class="item_description" type="text" name="item_description" value="{{ old('item_description') }}">
            @error('item_description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="subTitle">
            販売価格
        </div>
        <div class="inputAreaForCost">
            <input class="item_cost" type="text" name="item_cost" value="{{ old('item_cost') }}">
            @error('item_cost')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="buttonArea">
            <button class="postingButton" type="submit">出品する</button>
        </div>

    </form>



    <script>
        // 画像を選択した後のプレビューを表示する関数
        function previewImage(event) {
            const file = event.target.files[0]; // 選択したファイルを取得

            if (file) {
                const reader = new FileReader();

                // ファイルが読み込まれた時の処理
                reader.onload = function (e) {
                    const imagePreview = document.getElementById('imagePreview');
                    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
                    const imageArea = document.getElementById('imageArea');

                    // プレビュー画像を設定
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block'; // 画像を表示

                    // 「画像変更」ボタンを表示
                    imagePreviewContainer.style.display = 'block'; // ボタンを表示
                    imageArea.querySelector('.defaultDisplay').style.display = 'none'; // デフォルトの表示を非表示

                }

                // ファイルを読み込む
                reader.readAsDataURL(file);
            }
        }

        // 画像変更ボタンを押した際に画像選択を再度行えるようにする関数
        function resetImage() {
            const fileInput = document.getElementById('product_image');
            fileInput.value = ''; // ファイル入力の値をリセット
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const imageArea = document.getElementById('imageArea');

            imagePreview.style.display = 'none'; // プレビュー画像を非表示
            imagePreviewContainer.style.display = 'none'; // ボタンを非表示
            imageArea.querySelector('.defaultDisplay').style.display = 'block'; // デフォルトの画像選択表示を表示

            // 画像選択ボタンが元の位置に戻る
            imageArea.querySelector('.uploadButtonArea').style.display = 'block'; // 画像選択ボタンを再表示
        }

    </script>

@endsection