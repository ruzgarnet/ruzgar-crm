<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('message_id');
            $table->boolean('is_sent')->default(0);
            $table->timestamp('delivery_date')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('message_id')
                ->references('id')
                ->on('messages')
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
        Schema::dropIfExists('sms_list');
    }
}
