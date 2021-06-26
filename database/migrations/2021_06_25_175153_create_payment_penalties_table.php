<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id');
            $table->timestamp('created_at')->useCurrent();

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
        Schema::dropIfExists('payment_penalties');
    }
}
