<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomeLivewire extends Component
{
    public $auth;
    public $totalIncome;
    public $totalExpense;
    public $balance;
    public $transactionCount;

   public function mount()
{
    // Kalau belum login, redirect setelah komponen dirender
    if (!Auth::check()) {
        return $this->redirectRoute('auth.login');
    }

    // Kalau login, simpan data user
    $this->auth = Auth::user();

    // Hitung statistik
    $this->calculateStatistics();
}


    public function calculateStatistics()
    {
        // Total pemasukan
        $this->totalIncome = Transaction::where('user_id', $this->auth->id)
            ->where('type', 'income')
            ->sum('amount');
        
        // Total pengeluaran
        $this->totalExpense = Transaction::where('user_id', $this->auth->id)
            ->where('type', 'expense')
            ->sum('amount');
        
        // Saldo
        $this->balance = $this->totalIncome - $this->totalExpense;
        
        // Jumlah transaksi
        $this->transactionCount = Transaction::where('user_id', $this->auth->id)->count();
    }

    public function render()
    {
        // Transaksi terbaru (5 terakhir)
        $recentTransactions = Transaction::where('user_id', $this->auth->id)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pages.livewire-home-livewire', [
            'recentTransactions' => $recentTransactions
        ]);
    }
}