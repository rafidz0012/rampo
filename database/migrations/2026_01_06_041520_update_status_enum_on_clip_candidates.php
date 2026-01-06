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
        DB::statement("
            ALTER TABLE clip_candidates
            MODIFY status ENUM(
                'pending',
                'processing',
                'processed',
                'clipped',
                'failed'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE clip_candidates
            MODIFY status ENUM(
                'pending',
                'processed'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
