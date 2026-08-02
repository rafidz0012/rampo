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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');                        // Title / Judul
            $table->string('creditor_name');               // Nama pemberi utang
            $table->decimal('total', 15, 2);               // Total nominal utang
            $table->decimal('remaining_amount', 15, 2);    // Sisa hutang
            $table->date('due_date')->nullable();          // Jatuh tempo
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending'); // Status
            $table->text('note')->nullable();              // Note / Catatan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
