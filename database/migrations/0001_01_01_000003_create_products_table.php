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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock')->default(1);
            $table->string('short_description');
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->nullableMorphs('productable');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        Schema::create('plant_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_id')->nullable()->constrained('species')->onDelete('set null');
            $table->string('care_level')->nullable();
            $table->string('light')->nullable();
            $table->string('watering')->nullable();
            $table->string('pot_size')->nullable();
        });

        Schema::create('pot_details', function (Blueprint $table) {
            $table->id();
            $table->string('material')->nullable();
            $table->string('shape')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('color')->nullable();
        });

        Schema::create('media_details', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('weight')->nullable();
            $table->string('volume')->nullable();
        });

        Schema::create('fertilizer_details', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('form')->nullable();
            $table->string('weight')->nullable();
        });

        Schema::create('tool_details', function (Blueprint $table) {
            $table->id();
            $table->string('material')->nullable();
            $table->string('brand')->nullable();
            $table->string('weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_details');
        Schema::dropIfExists('pot_details');
        Schema::dropIfExists('media_details');
        Schema::dropIfExists('fertilizer_details');
        Schema::dropIfExists('tool_details');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
