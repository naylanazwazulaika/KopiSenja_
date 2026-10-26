<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sale_number', 50)->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('order_type', 30)->default('pos'); // pos, dine_in, take_away
            $table->decimal('total', 14, 2)->default(0);
            $table->string('payment_method', 30)->default('cash'); // cash, qris, transfer
            $table->string('status', 30)->default('completed'); // pending, confirmed, preparing, ready, completed, cancelled
            $table->text('notes')->nullable();
            $table->dateTime('sold_at');
            $table->timestamps();

            $table->index('sold_at');
            $table->index('status');
            $table->index('order_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
