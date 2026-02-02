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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('fullName');
            $table->string('email');
            $table->string('phoneNo');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('zipCode');
            $table->string('country');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};