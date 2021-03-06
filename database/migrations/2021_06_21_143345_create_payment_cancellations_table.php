<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id');
            $table->foreignId('payment_id');
            $table->string('description', 511);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
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
        Schema::dropIfExists('payment_cancellations');
    }
}
