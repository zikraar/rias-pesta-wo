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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('title');                     // Judul tahapan, contoh: "Survey Lokasi"
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'on_progress', 'done'])->default('pending');
            $table->integer('order')->default(0);        // Urutan tampil
            $table->date('target_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->string('attachment')->nullable();    // Foto/dokumen progress
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
