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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('month'); // Format: YYYY-MM
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('half_days')->default(0);
            $table->decimal('total_ot_hours', 8, 2)->default(0);
            $table->decimal('total_advance_amount', 10, 2)->default(0);
            $table->decimal('base_salary', 10, 2)->default(0); // (present_days / 30) * monthly_salary
            $table->decimal('ot_amount', 10, 2)->default(0); // total_ot_hours * ot_rate_per_hour
            $table->decimal('final_salary', 10, 2)->default(0); // base_salary + ot_amount - total_advance_amount
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure one salary record per employee per month
            $table->unique(['employee_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
