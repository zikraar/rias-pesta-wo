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
        // Tabel ini di beberapa environment sudah punya kolom standar (dibuat dari SQL dump),
        // jadi setiap kolom ditambahkan secara idempotent agar migrasi aman dijalankan di fresh install maupun DB existing.
        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->after('id');
            }
            if (! Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->morphs('notifiable');
            }
            if (! Schema::hasColumn('notifications', 'data')) {
                $table->text('data');
            }
            if (! Schema::hasColumn('notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->dropMorphs('notifiable');
            }
            $table->dropColumn(array_filter(['type', 'data', 'read_at'], fn ($c) => Schema::hasColumn('notifications', $c)));
        });
    }
};
