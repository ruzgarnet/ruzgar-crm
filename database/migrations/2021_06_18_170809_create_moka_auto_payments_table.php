<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMokaAutoPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moka_auto_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->foreignId('payment_id');
            $table->bigInteger('moka_plan_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('sale_id')
                ->references('id')
                ->on('moka_sales')
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
        Schema::dropIfExists('moka_auto_payments');
    }
}
