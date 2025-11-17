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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Cash', 'Credit'])->default('Cash');
            $table->string('contact')->nullable();
            $table->string('ntn')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('vendors');
    }
};
