<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('title');                        // Judul Piutang (ex: Pinjaman Budi)
            $table->string('debtor_name');                 // Nama peminjam / pihak penunggak
            $table->decimal('total', 15, 2);               // Total piutang awal
            $table->decimal('remaining_amount', 15, 2);    // Sisa piutang yang belum dibayar
            $table->date('due_date')->nullable();          // Tanggal jatuh tempo
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->text('note')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
