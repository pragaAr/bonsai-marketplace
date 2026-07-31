<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_requests', function (Blueprint $table): void {
            $table->dropUnique(['store_slug']);
            $table->unique('store_name');
        });
    }

    public function down(): void
    {
        Schema::table('seller_requests', function (Blueprint $table): void {
            $table->dropUnique(['store_name']);
            $table->unique('store_slug');
        });
    }
};
