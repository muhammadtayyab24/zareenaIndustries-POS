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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('ntn')->nullable(); // National Tax Number
            $table->string('strn')->nullable(); // Sales Tax Registration Number
            $table->string('tel_no')->nullable(); // Telephone Number
            $table->string('mobile_no')->nullable(); // Mobile Number
            $table->string('website')->nullable();
            $table->string('logo')->nullable(); // Logo path
            $table->string('favicon')->nullable(); // Favicon path
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
        Schema::dropIfExists('companies');
    }
};
