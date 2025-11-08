<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // tambahkan ini biar bisa akses model Transaction

class TransactionController extends Controller
{
    public function index()
    {
        return view('pages.app.transactions.index');
    }
    
    public function detail($transaction_id)
    {
        // Ambil data transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($transaction_id);

        // Kirim data ke view
        return view('pages.app.transactions.detail', compact('transaction'));
    }

      public function statistics()
    {
        return view('pages.app.transactions.statistics');
    }
}
