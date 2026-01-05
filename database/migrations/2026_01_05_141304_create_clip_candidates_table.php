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
        Schema::create('clip_candidates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('video_id')
                  ->constrained('videos')
                  ->cascadeOnDelete();

            $table->decimal('start_seconds', 10, 3);
            $table->decimal('end_seconds', 10, 3);
            $table->decimal('duration', 6, 2);

            $table->integer('score')->default(0);

            $table->text('preview')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->timestamps();

            // Index untuk performa
            $table->index(['video_id', 'score']);
            $table->index(['video_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clip_candidates');
    }
};
