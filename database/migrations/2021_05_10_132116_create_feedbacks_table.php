<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->default(null);
            $table->string('name', 63);
            $table->string('phone', 15);
            $table->string('email', 63);
            $table->text('message');
            $table->timestamp('seen_at')->nullable()->default(null);
            $table->foreignId('staff_id')->nullable()->default(null);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('customer_id')
            	->references('id')
            	->on('customers')
            	->onUpdate('CASCADE')
            	->onDelete('CASCADE');
            	
            $table->foreign('staff_id')
            	->references('id')
            	->on('staffs')
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
        Schema::dropIfExists('feedbacks');
    }
}
