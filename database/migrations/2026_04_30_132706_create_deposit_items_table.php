<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('deposit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained('deposits');
            $table->foreignId('category_id')->constrained('categories');
            $table->decimal('weight', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_items');
    }
};
