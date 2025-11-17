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
            $table->foreignId('cat_id')->constrained('product_categories')->onDelete('restrict');
            $table->foreignId('type_id')->constrained('product_types')->onDelete('restrict');
            $table->string('product_name');
            $table->string('unit_type')->nullable();
            $table->decimal('opening_qty', 10, 2)->default(0);
            $table->decimal('current_qty', 10, 2)->default(0);
            $table->tinyInteger('status')->default(1); // 0 = inactive, 1 = active
            $table->boolean('is_deleted')->default(false);
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
