<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('message_id');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('staff_id');
            $table->string('phone');
            $table->tinyInteger('type')->default(1);
            $table->string("message", 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
            // TYPE, category_id, message, category_id ve customer_id => nullable, phone, staff_id
            // 1 = Normal, 2 = Toplu, 3 = Custom
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
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('sent_messages');
    }
}
