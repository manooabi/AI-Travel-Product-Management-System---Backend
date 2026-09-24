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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
             $table->string('product_name');
            $table->string('destination');
            $table->string('category');

            $table->text('description');

            $table->json('highlights')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('tags')->nullable();

            $table->decimal('price', 10, 2);
            $table->unsignedInteger('inventory_count');

            $table->dateTime('valid_from');
            $table->dateTime('valid_until');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
