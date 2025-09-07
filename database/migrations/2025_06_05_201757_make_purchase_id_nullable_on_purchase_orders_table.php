<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Change purchase_id to string and nullable
            $table->string('purchase_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Revert purchase_id back to previous type (likely bigint unsigned and not nullable)
            $table->unsignedBigInteger('purchase_id')->nullable(false)->change();
        });
    }
};
