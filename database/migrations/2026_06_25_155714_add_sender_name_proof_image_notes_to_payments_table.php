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
        // Beberapa environment sudah punya kolom ini (dibuat dari SQL dump), jadi ditambahkan secara idempotent.
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'sender_name')) {
                $table->string('sender_name')->nullable()->after('account_name');
            }
            if (! Schema::hasColumn('payments', 'proof_image')) {
                $table->string('proof_image')->nullable()->after('transfer_proof');
            }
            if (! Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable()->after('transfer_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(array_filter(
                ['sender_name', 'proof_image', 'notes'],
                fn ($c) => Schema::hasColumn('payments', $c)
            ));
        });
    }
};
