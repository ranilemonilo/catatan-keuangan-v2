<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatisticsLivewire extends Component
{
    public $auth;
    public $selectedYear;
    public $availableYears = [];
    
    // Summary Statistics
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $balance = 0;
    public $transactionCount = 0;
    
    // Chart Data
    public $monthlyData = [];
    public $categoryData = [];
    public $typeComparisonData = [];

    public function mount()
    {
        $this->auth = Auth::user();
        
        // Get available years from transactions
        $this->availableYears = Transaction::where('user_id', $this->auth->id)
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM transaction_date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Set default year to current year or latest year with data
        $this->selectedYear = in_array(date('Y'), $this->availableYears) 
            ? date('Y') 
            : (!empty($this->availableYears) ? $this->availableYears[0] : date('Y'));
        
        $this->loadStatistics();
    }

    public function updatedSelectedYear()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Summary statistics for selected year
        $yearTransactions = Transaction::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear);
        
        $this->totalIncome = (clone $yearTransactions)->where('type', 'income')->sum('amount');
        $this->totalExpense = (clone $yearTransactions)->where('type', 'expense')->sum('amount');
        $this->balance = $this->totalIncome - $this->totalExpense;
        $this->transactionCount = (clone $yearTransactions)->count();
        
        // Monthly data for line chart
        $this->monthlyData = $this->getMonthlyData();
        
        // Category data for pie chart
        $this->categoryData = $this->getCategoryData();
        
        // Type comparison data for bar chart
        $this->typeComparisonData = $this->getTypeComparisonData();
    }

    private function getMonthlyData()
    {
        $monthlyIncome = Transaction::where('user_id', $this->auth->id)
            ->where('type', 'income')
            ->whereYear('transaction_date', $this->selectedYear)
            ->selectRaw('EXTRACT(MONTH FROM transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        $monthlyExpense = Transaction::where('user_id', $this->auth->id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $this->selectedYear)
            ->selectRaw('EXTRACT(MONTH FROM transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $incomeData[] = $monthlyIncome[$i] ?? 0;
            $expenseData[] = $monthlyExpense[$i] ?? 0;
        }
        
        return [
            'income' => $incomeData,
            'expense' => $expenseData
        ];
    }

    private function getCategoryData()
    {
        $categoryExpenses = Transaction::where('user_id', $this->auth->id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $this->selectedYear)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return [
            'categories' => $categoryExpenses->pluck('category')->toArray(),
            'amounts' => $categoryExpenses->pluck('total')->toArray()
        ];
    }

    private function getTypeComparisonData()
    {
        $comparison = Transaction::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
        
        return [
            'income' => $comparison['income'] ?? 0,
            'expense' => $comparison['expense'] ?? 0
        ];
    }

    public function render()
    {
        return view('livewire.statistics-livewire');
    }
}