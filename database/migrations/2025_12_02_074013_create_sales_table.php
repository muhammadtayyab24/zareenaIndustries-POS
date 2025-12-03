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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['tax', 'non_tax'])->default('non_tax');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('order_taker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('salesman_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('invoice_no');
            $table->string('po_no')->nullable();
            $table->string('dc_no')->nullable();
            $table->date('due_date')->nullable();
            $table->date('invoice_date');
            $table->decimal('freight_charges', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total_gst', 10, 2)->default(0);
            $table->decimal('adv_inc_tax_percentage', 5, 2)->default(0);
            $table->decimal('adv_inc_tax_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
