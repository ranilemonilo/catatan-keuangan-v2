<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', ['income', 'expense']); // pemasukan atau pengeluaran
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2); // jumlah uang
            $table->string('category'); // kategori transaksi
            $table->date('transaction_date'); // tanggal transaksi
            $table->string('receipt')->nullable(); // foto bukti transaksi
            $table->timestamps();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};