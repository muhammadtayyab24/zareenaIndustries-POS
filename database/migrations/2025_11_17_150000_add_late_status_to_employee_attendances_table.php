<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include 'late'
        DB::statement("ALTER TABLE employee_attendances MODIFY COLUMN status ENUM('present', 'absent', 'half_day', 'late') DEFAULT 'present'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum (remove 'late')
        DB::statement("ALTER TABLE employee_attendances MODIFY COLUMN status ENUM('present', 'absent', 'half_day') DEFAULT 'present'");
    }
};

