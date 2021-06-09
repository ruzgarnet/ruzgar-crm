<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id');
            $table->foreignId('service_id');
            $table->foreignId('customer_id');
            $table->string("subscription_no")->unique()->nullable()->default(null);
            $table->string('bbk_code')->nullable()->default(null);;
            $table->unsignedTinyInteger('commitment')->nullable()->default(null);
            $table->date('start_date');
            $table->date('end_date')->nullable()->default(null);
            $table->unsignedDecimal('price');
            $table->unsignedDecimal('payment')->default(0);
            $table->json('options')->nullable()->default(null);
            $table->json('values')->nullable()->default(null);
            $table->timestamp('approved_at')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
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
        Schema::dropIfExists('subscriptions');
    }
}
