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
        Schema::table('purchases', function (Blueprint $table) {
            $table->date('po_date')->nullable()->after('po_no');
            $table->string('delivery_challan_no')->nullable()->after('grn_no');
            $table->date('delivery_challan_date')->nullable()->after('delivery_challan_no');
            $table->decimal('adv_inc_tax_percentage', 5, 2)->default(0)->after('total_gst');
            $table->decimal('adv_inc_tax_amount', 10, 2)->default(0)->after('adv_inc_tax_percentage');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('adv_inc_tax_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'po_date',
                'delivery_challan_no',
                'delivery_challan_date',
                'adv_inc_tax_percentage',
                'adv_inc_tax_amount',
                'discount_percentage',
                'discount_amount'
            ]);
        });
    }
};
