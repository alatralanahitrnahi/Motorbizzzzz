<?php


   use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by')->nullable()->after('id');

            $table->foreign('generated_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('barcodes', function (Blueprint $table) {
            $table->dropForeign(['generated_by']);
            $table->dropColumn('generated_by');
        });
    }

};