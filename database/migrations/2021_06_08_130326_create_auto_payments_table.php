<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
            $table->foreignId('moka_sale_id')->nullable()->default(null);
            $table->foreignId('moka_customer_id')->nullable()->default(null);
            $table->string('moka_card_token', 255)->nullable()->default(null);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('auto_payments');
    }
}
