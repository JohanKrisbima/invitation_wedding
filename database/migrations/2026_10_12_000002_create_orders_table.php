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
            $table->string('order_number')->unique();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
            $table->string('customer_name');
            $table->text('customer_address')->nullable();
            $table->date('wedding_date');
            $table->string('customer_phone');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->string('order_status')->default('pending');  // pending, in_progress, completed, cancelled
            $table->text('notes')->nullable();
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
