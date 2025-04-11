<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchaser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('item_name', 255);
            $table->string('shipping_postal_code', 255);
            $table->string('shipping_address', 255);
            $table->string('shipping_building_name', 255);
            $table->string('payment_method', 255);
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
        Schema::dropIfExists('purchased_items');
    }
}
