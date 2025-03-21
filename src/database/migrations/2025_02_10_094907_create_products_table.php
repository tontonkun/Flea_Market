<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name', 255);
            $table->integer('price');
            $table->string('brand_name', 255)->nullable();
            $table->string('product_img_pass', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->foreignId('condition_id')->nullable()->constrained('conditions')->onDelete('set null');
            $table->boolean('is_active')->default(true);
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
        Schema::table('products', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['user_id']);
            $table->dropForeign(['condition_id']);
        });

        // テーブル削除
        Schema::dropIfExists('products');
    }
}
