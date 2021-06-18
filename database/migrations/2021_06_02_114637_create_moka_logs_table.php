<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMokaLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moka_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id');
            $table->ipAddress('ip');
            $table->string('trx_code')->nullable()->default(null);
            $table->json('response')->nullable()->default(null);
            $table->unsignedTinyInteger('type')->default(1);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

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
        Schema::dropIfExists('moka_logs');
    }
}
