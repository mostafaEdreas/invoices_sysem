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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained()->nullable();
            $table->foreignId('user_id')->constrained();
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('discount', 8, 2)->default( 0);
            $table->decimal('total', 10, 2)->default( 0);
            $table->timestamp('invoice_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
