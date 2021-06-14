<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id')->comment('Referens olanın aboneliği');
            $table->foreignId('referenced_id')->comment('Yeni gelenin aboneliği');
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('staff_id')->nullable()->default(null);
            $table->timestamp('decided_at')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('reference_id')
                ->references('id')
                ->on('subscriptions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('referenced_id')
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
        Schema::dropIfExists('references');
    }
}
