<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_requests', function (Blueprint $table) {
            $table->string('store_slug')->nullable()->after('store_name');
        });

        // Preserve existing data while making every historical slug unique.
        $usedSlugs = [];

        DB::table('seller_requests')
            ->orderBy('id')
            ->get(['id', 'store_name'])
            ->each(function (object $request) use (&$usedSlugs): void {
                $baseSlug = Str::slug($request->store_name) ?: 'toko-'.$request->id;
                $slug = $baseSlug;
                $suffix = 2;

                while (in_array($slug, $usedSlugs, true)) {
                    $slug = $baseSlug.'-'.$suffix++;
                }

                $usedSlugs[] = $slug;

                DB::table('seller_requests')
                    ->where('id', $request->id)
                    ->update(['store_slug' => $slug]);
            });

        Schema::table('seller_requests', function (Blueprint $table) {
            $table->string('store_slug')->nullable(false)->change();
            $table->unique('store_slug');
        });
    }

    public function down(): void
    {
        Schema::table('seller_requests', function (Blueprint $table) {
            $table->dropUnique(['store_slug']);
            $table->dropColumn('store_slug');
        });
    }
};
