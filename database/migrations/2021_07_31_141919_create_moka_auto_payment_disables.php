<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMokaAutoPaymentDisables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moka_auto_payment_disables', function (Blueprint $table) {
            $table->id();
            $table->foreignId("subscription_id");
            $table->foreignId("payment_id");
            $table->decimal("old_price");
            $table->decimal("new_price");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('subscription_id')
            	->references('id')
            	->on('subscriptions')
            	->onUpdate('CASCADE')
            	->onDelete('CASCADE');

            $table->foreign('payment_id')
            	->references('id')
            	->on('payments')
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
        Schema::dropIfExists('moka_auto_payment_disables');
    }
}
