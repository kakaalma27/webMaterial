<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('sales', function (Blueprint $table) {
        $table->foreignId('payment_id')->after('productable_type')->constrained('payments')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropForeign(['payment_id']);
        $table->dropColumn('payment_id');
    });
}
};
