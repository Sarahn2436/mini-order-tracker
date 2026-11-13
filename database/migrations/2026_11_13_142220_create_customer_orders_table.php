<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('customerorders', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('shipping_address');
        $table->string('shipping_city');
        $table->string('shipping_zip');
        $table->integer('amount_cents');
        $table->string('stripe_payment_id'); // Keeps track of Stripe's receipt ID
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
