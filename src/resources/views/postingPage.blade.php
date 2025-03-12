@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/postingPage.css') }}">
@endsection

@section('content')
    <form class="form" action="/postItems" method="POST">
        @csrf
        <div class="mainTitleArea">
            商品の出品
        </div>

        <div class="subTitle">
            商品画像
        </div>
        <div class="imageSettingArea">
            <div class="imageArea">
                @if(Auth::user()->product_img_pass)
                    <img src="{{ asset('storage/product_images/' . Auth::user()->product_img_pass) }}" alt="Product Image"
                        class="itemImage">
                @else 
                    <div class="defaultDisplay">
                        <div class="uploadButtonArea">
                            <input type="file" id="product_image" name="product_image" class="fileInput">
                            <label for="product_image">画像を選択する</label>
                        </div>
                    </div>
                @endif
            </div>
            @error('profile_image')
                <div class="error">{{ $message }}</div>
            @enderror
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
            <select class="item_state" name="item_state">
                <option value="">選択してください</option>
                <option value="good" {{ old('item_state') == 'good' ? 'selected' : '' }}>良好</option>
                <option value="no_visible_damage" {{ old('item_state') == 'no_visible_damage' ? 'selected' : '' }}>目立った傷や汚れなし
                </option>
                <option value="slightly_damaged" {{ old('item_state') == 'slightly_damaged' ? 'selected' : '' }}>やや傷や汚れあり
                </option>
                <option value="bad_condition" {{ old('item_state') == 'bad_condition' ? 'selected' : '' }}>状態が悪い</option>
            </select>
            @error('item_state')
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
        <div class="inputArea">
            <input class="item_cost" type="text" name="item_cost" value="{{ old('item_cost') }}">
            @error('item_cost')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="buttonArea">
            <button class="postiingButton" type="submit">出品する</button>
        </div>

    </form>
@endsection