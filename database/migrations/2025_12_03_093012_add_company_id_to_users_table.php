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
        Schema::table('users', function (Blueprint $table) {
            // Drop existing unique constraint on email
            $table->dropUnique(['email']);
            
            // Add company_id column (nullable for super admin)
            $table->foreignId('company_id')->nullable()->after('id')
                ->constrained('companies')->onDelete('cascade');
            
            // Add composite unique constraint: email + company_id
            // This allows same email in different companies but unique within a company
            $table->unique(['email', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop composite unique
            $table->dropUnique(['email', 'company_id']);
            
            // Drop company_id foreign key
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            
            // Restore original unique constraint on email
            $table->unique('email');
        });
    }
};
