@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mainPage.css') }}">
@endsection

{{-- フラッシュメッセージの表示 --}}
@if(session('success'))
    <div class="postingAnnounce">
        {{ session('success') }}
    </div>
@endif

@section('content')
    <form action="/mainPage/myList" method="GET"></form>
    <div class="displaySelectionArea">
        @csrf
        <div class="recomments">おすすめ</div>
        <button class="myList">マイリスト</button>
    </div>
    </form>

    <div class="displayArea">
        <div id="myListItems" class="itemList" style="display: none;">
            @foreach ($favoriteItems as $favorite)
                @if ($favorite->item)
                    <div class="itemItem">
                        <div class="itemImageContainer">
                            <a href="{{ route('item.showDetail', $favorite->item->id) }}">
                                @if ($favorite->item->item_img_pass)
                                    <img src="{{ asset('/' . $favorite->item->item_img_pass) }}" class="itemImage">
                                @else
                                    <div class="defaultItemImage">No Image</div>
                                @endif
                            </a>
                        </div>
                        <div class="itemName">{{ $favorite->item->item_name }}</div>
                    </div>
                @endif
            @endforeach
        </div>

@endsection