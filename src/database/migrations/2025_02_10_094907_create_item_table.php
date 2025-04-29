<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('item_name', 255);
            $table->integer('price');
            $table->string('brand_name', 255)->nullable();
            $table->string('item_img_pass', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->foreignId('condition_id')->nullable()->constrained('conditions')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->boolean('in_trade')->default(false);
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['condition_id']);
        });

        // テーブル削除
        Schema::dropIfExists('items');
    }
}
