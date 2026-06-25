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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();        // AUTO: WO-2026-XXXX
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('event_date');
            $table->string('event_location');
            $table->integer('guest_count');
            $table->enum('event_type', ['akad', 'resepsi', 'akad_resepsi']);
            $table->string('groom_name');
            $table->string('bride_name');
            $table->text('special_requests')->nullable();
            $table->decimal('total_price', 15, 2)->default(0);
            $table->enum('status', [
                'pending',       // Menunggu konfirmasi admin
                'confirmed',     // Dikonfirmasi admin
                'in_progress',   // Sedang dalam pengerjaan
                'completed',     // Selesai
                'cancelled'      // Dibatalkan
            ])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
