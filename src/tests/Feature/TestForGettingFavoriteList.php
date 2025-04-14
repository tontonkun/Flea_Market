<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestForGettingFavoriteList extends TestCase
{
    use RefreshDatabase;

    // テスト用のセットアップ
    protected function setUp(): void
    {
        parent::setUp();

        // テスト用のユーザーを作成
        $this->user = User::factory()->create();

        // テスト用の商品を作成
        $this->item1 = Item::factory()->create([
            'item_name' => 'Item 1',
            'is_active' => true,
        ]);
        $this->item2 = Item::factory()->create([
            'item_name' => 'Item 2',
            'is_active' => false,
        ]);
        $this->item3 = Item::factory()->create([
            'item_name' => 'Item 3',
            'is_active' => true,
        ]);

        // ユーザーが「いいね」した商品を作成
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item1->id,
        ]);
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item3->id,
        ]);
    }

    /** @test */
    public function it_shows_only_favorite_items_for_logged_in_users()
    {
        // ログイン状態でリクエストを送る
        $response = $this->actingAs($this->user)
                         ->get('/'); // トップページまたは対象ページにアクセス

        // ログインしている場合、`myListItems` 内で「いいね」した商品だけが表示される
        $response->assertSee($this->item1->item_name); // "Item 1" は表示されるべき
        $response->assertSee($this->item3->item_name); // "Item 3" は表示されるべき
        $response->assertDontSee($this->item2->item_name); // "Item 2" は表示されないべき
    }

    /** @test */
    public function it_shows_sold_message_on_favorite_items()
    {
        // ログインユーザーを作成
        $this->user = User::factory()->create();

        // 商品を作成（購入済みの商品も含む）
        $this->item1 = Item::factory()->create([
            'item_name' => 'Item 1',
            'is_active' => true, // 購入されていない
        ]);
        $this->item2 = Item::factory()->create([
            'item_name' => 'Item 2',
            'is_active' => false, // 購入された商品（非アクティブ）
        ]);

        // 「いいね」した商品を作成
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item1->id,
        ]);
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item2->id, // 購入済み商品も「いいね」
        ]);

        // ログイン状態でリクエストを送る
        $response = $this->actingAs($this->user)
                        ->get('/'); // トップページまたは対象ページにアクセス

        // 商品リスト内に「Sold」のラベルが表示されているか確認
        $response->assertSee('<div class="sold-label">Sold</div>', false); // HTMLタグとして検索
    }

    /** @test */
    public function it_does_not_show_own_items_in_favorite_list()
    {
        // ログインユーザーを作成
        $this->user = User::factory()->create();

        // 他のユーザー（出品者）を作成
        $anotherUser = User::factory()->create();

        // ユーザーが出品した商品を作成（自分が出品した商品）
        $this->userItem = Item::factory()->create([
            'seller_id' => $this->user->id,  // 出品者は自分
            'item_name' => 'My Item',
            'is_active' => true,
        ]);

        // 他のユーザーが出品した商品を作成
        $this->anotherUserItem = Item::factory()->create([
            'seller_id' => $anotherUser->id,  // 出品者は別のユーザー
            'item_name' => 'Other Item',
            'is_active' => true,
        ]);

        // 「いいね」した商品を作成
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->userItem->id, // 自分の出品した商品
        ]);
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->anotherUserItem->id, // 他のユーザーの出品した商品
        ]);

        // ログイン状態でリクエストを送る
        $response = $this->actingAs($this->user)
                        ->get('/'); // トップページまたは対象ページにアクセス

        // 自分が出品した商品は「マイリスト」に表示されないことを確認
        $response->assertDontSee($this->userItem->item_name); // "My Item" は表示されない

        // 他のユーザーが出品した商品は表示されることを確認
        $response->assertSee($this->anotherUserItem->item_name); // "Other Item" は表示されるべき
    }

     /** @test */
    public function guest_cannot_see_any_favorite_items()
    {
        // 未ログイン状態でトップページにアクセス
        $response = $this->get('/');

        // ステータスコード確認
        $response->assertStatus(200);

        // レスポンスから myListItems 部分だけ抜き出す
        $html = $response->getContent();

        // DOMとして解析
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        // id="myListItems" の中身を取得
        $myListItems = $xpath->query('//*[@id="myListItems"]')->item(0);

        // nullチェック（存在しているか）
        $this->assertNotNull($myListItems, '#myListItems が存在しません');

        // 中身が空（または itemArea を含まない）ことを検証
        $this->assertStringNotContainsString('itemArea', $myListItems->C14N());
    }
}