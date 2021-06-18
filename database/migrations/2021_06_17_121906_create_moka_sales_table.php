<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMokaSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moka_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId("subscription_id");
            $table->tinyInteger('moka_customer_id');
            $table->tinyInteger('moka_sale_id');
            $table->string('moka_card_token');
            $table->boolean("active")->default(true);
            $table->timestamp('disabled_at')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moka_sales');
    }
}
