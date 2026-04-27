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
        Schema::create('electric_bills', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usage_id')->constrained('electric_usages')->onDelete('cascade');
    $table->decimal('bill_amount', 10, 2);
    $table->date('due_date');
    $table->string('status')->default('unpaid');
    $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electric_bills');
    }
};
