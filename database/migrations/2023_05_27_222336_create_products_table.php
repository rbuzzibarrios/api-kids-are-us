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
            $table->string('name', 200);
            $table->string('sku', 50);
            $table->decimal('price');
            $table->foreignId('product_category_id')
                ->nullable()
                ->constrained('product_categories')
                ->restrictOnUpdate()
                ->nullOnDelete();

            $table->json('tags')->nullable();
            $table->text('description')->nullable();
            $table->text('additional_information')->nullable();
            $table->unsignedInteger('rate')->default(0);
            $table->json('images')->nullable();

            $table->timestamps();
            $table->softDeletes();
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
