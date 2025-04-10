<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ItemSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'item_name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_id' => 1, // '良好'
            ],
            [
                'item_name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_id' => 2, // '目立った傷や汚れなし'
            ],
            [
                'item_name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_id' => 3, // 'やや傷や汚れあり'
            ],
            [
                'item_name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4, // '状態が悪い'
            ],
            [
                'item_name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_id' => 1, // '良好'
            ],
            [
                'item_name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_id' => 2, // '目立った傷や汚れなし'
            ],
            [
                'item_name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_id' => 3, // 'やや傷や汚れあり'
            ],
            [
                'item_name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_id' => 4, // '状態が悪い'
            ],
            [
                'item_name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1, // '良好'
            ],
            [
                'item_name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' => 2, // '目立った傷や汚れなし'
            ],
        ];

        // 各アイテムに対して処理を行う
        foreach ($items as $item) {
        // 画像保存先ディレクトリ
        $directory = storage_path('app/public/item_images');

        // ディレクトリが存在しない場合に作成
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true); // 0755 はディレクトリのパーミッション
        }

        // 画像のURLを取得して、画像のコンテンツをダウンロード
        $imageContent = @file_get_contents($item['image_url']); // エラー抑制
        if ($imageContent === false) {
            \Log::error("画像のダウンロードに失敗: " . $item['image_url']);
            continue; // 失敗した場合はスキップ
        }

        $imageName = basename($item['image_url']); // ファイル名を取得

        // すでにエンコードされているかチェックし、必要な場合のみエンコード
        if (preg_match('/%[0-9A-F]{2}/', $imageName)) {
            // すでにエンコードされたファイル名はデコードして使用
            $encodedImageName = urldecode($imageName);
        } else {
            // エンコードされていない場合はエンコード
            $encodedImageName = urlencode($imageName);
        }

        $imagePath = 'item_images/' . $encodedImageName; // ファイル名をパスに追加

        // publicディスクに画像を保存
        Storage::disk('public')->put($imagePath, $imageContent);

        // アイテムを作成
        \App\Models\Item::create([
            'item_name' => $item['item_name'],
            'price' => $item['price'],
            'description' => $item['description'],
            'item_img_pass' => 'storage/' . $imagePath, // 保存した画像のパス（公開用のURL）
            'condition_id' => $item['condition_id'],
            'created_at' => now(),
            'updated_at' => now(),
            ]);
        }
    }
}
