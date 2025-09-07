<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Rename po_number to purchase_id
            $table->renameColumn('po_number', 'purchase_id');

            // Add new columns
            $table->string('supplier_contact')->nullable()->after('vendor_id');
            $table->decimal('gst_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('final_amount', 12, 2)->default(0)->after('gst_amount');
            $table->enum('payment_mode', ['cash', 'bank_transfer', 'cheque', 'credit'])->after('final_amount');
            $table->integer('credit_days')->nullable()->after('payment_mode');
          $table->date('order_date')->nullable()->after('credit_days');
            $table->date('expected_delivery')->nullable()->after('order_date');

            // Add new status values using enum workaround (depends on DB)
            $table->enum('status', ['pending', 'approved', 'received', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->renameColumn('purchase_id', 'po_number');

            $table->dropColumn([
                'supplier_contact',
                'gst_amount',
                'final_amount',
                'payment_mode',
                'credit_days',
                'order_date',
                'expected_delivery',
            ]);

            // Restore original status values
            $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending')->change();
        });
    }
};
