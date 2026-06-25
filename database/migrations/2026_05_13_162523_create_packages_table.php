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
        Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');                          // Nama paket
        $table->enum('category', ['basic', 'standard', 'premium', 'custom']);
        $table->text('description');
        $table->decimal('price', 15, 2);
        $table->integer('max_guests')->default(0);
        $table->json('includes');                        // List item yang termasuk (JSON array)
        $table->string('thumbnail')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
