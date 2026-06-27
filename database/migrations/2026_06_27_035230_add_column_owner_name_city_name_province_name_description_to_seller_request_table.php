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
        Schema::table('seller_requests', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->after('store_name');
            $table->string('city_name')->nullable()->after('owner_name');
            $table->string('province_name')->nullable()->after('city_name');
            $table->text('description')->nullable()->after('province_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_requests', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'city_name', 'province_name', 'description']);
        });
    }
};
