<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class TransactionLivewire extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $auth;
    
    // Search & Filter
    public $search = '';
    public $filterType = '';
    public $filterCategory = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    
    // Add Transaction
    public $addType;
    public $addTitle;
    public $addDescription;
    public $addAmount;
    public $addCategory;
    public $addTransactionDate;
    public $addReceipt;
    
    // Edit Transaction
    public $editId;
    public $editType;
    public $editTitle;
    public $editDescription;
    public $editAmount;
    public $editCategory;
    public $editTransactionDate;
    
    // Edit Receipt
    public $editReceiptId;
    public $editReceiptFile;
    
    // Delete Transaction
    public $deleteId;
    public $deleteTitle;
    public $deleteConfirmTitle;

    public function mount()
    {
        $this->auth = Auth::user();
        $this->addTransactionDate = date('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterCategory = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Transaction::where('user_id', $this->auth->id);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'ilike', '%' . $this->search . '%')
                  ->orWhere('description', 'ilike', '%' . $this->search . '%');
            });
        }

        // Filter by type
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        // Filter by category
        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        // Filter by date range
        if ($this->filterDateFrom) {
            $query->whereDate('transaction_date', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $query->whereDate('transaction_date', '<=', $this->filterDateTo);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

        // Get all categories for filter
        $categories = Transaction::where('user_id', $this->auth->id)
                                 ->distinct()
                                 ->pluck('category');

        return view('livewire.transaction-livewire', [
            'transactions' => $transactions,
            'categories' => $categories
        ]);
    }

    // Add Transaction
    public function addTransaction()
{
    $this->validate([
        'addType' => 'required|in:income,expense',
        'addTitle' => 'required|string|max:255',
        'addDescription' => 'nullable|string',
        'addAmount' => 'required|numeric|min:0',
        'addCategory' => 'required|string|max:100',
        'addTransactionDate' => 'required|date',
        'addReceipt' => 'nullable|image|max:2048',
    ]);

    $receiptPath = null;
    if ($this->addReceipt) {
        $userId = $this->auth->id;
        $dateNumber = now()->format('YmdHis');
        $extension = $this->addReceipt->getClientOriginalExtension();
        $filename = $userId . '_' . $dateNumber . '.' . $extension;
        $receiptPath = $this->addReceipt->storeAs('receipts', $filename, 'public');
    }

    Transaction::create([
        'user_id' => $this->auth->id,
        'type' => $this->addType,
        'title' => $this->addTitle,
        'description' => $this->addDescription,
        'amount' => $this->addAmount,
        'category' => $this->addCategory,
        'transaction_date' => $this->addTransactionDate,
        'receipt' => $receiptPath,
    ]);

    $this->reset(['addType', 'addTitle', 'addDescription', 'addAmount', 'addCategory', 'addReceipt']);
    $this->addTransactionDate = date('Y-m-d');
    $this->dispatch('closeModal', id: 'addTransactionModal');
    $this->dispatch('clearTrixEditor', id: 'addDescription'); // Tambahkan ini
    $this->dispatch('showAlert', type: 'success', message: 'Transaksi berhasil ditambahkan!');
}

    // Prepare Edit
    public function prepareEdit($id)
    {
        $transaction = Transaction::find($id);
        
        if (!$transaction || $transaction->user_id !== $this->auth->id) {
            return;
        }

        $this->editId = $transaction->id;
        $this->editType = $transaction->type;
        $this->editTitle = $transaction->title;
        $this->editDescription = $transaction->description;
        $this->editAmount = $transaction->amount;
        $this->editCategory = $transaction->category;
        $this->editTransactionDate = $transaction->transaction_date;

        $this->dispatch('showModal', id: 'editTransactionModal');
    }

    // Edit Transaction
    public function editTransaction()
    {
        $this->validate([
            'editType' => 'required|in:income,expense',
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editAmount' => 'required|numeric|min:0',
            'editCategory' => 'required|string|max:100',
            'editTransactionDate' => 'required|date',
        ]);

        $transaction = Transaction::find($this->editId);

        if (!$transaction || $transaction->user_id !== $this->auth->id) {
            $this->addError('editTitle', 'Transaksi tidak ditemukan.');
            return;
        }

        $transaction->update([
            'type' => $this->editType,
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'amount' => $this->editAmount,
            'category' => $this->editCategory,
            'transaction_date' => $this->editTransactionDate,
        ]);

        $this->reset(['editId', 'editType', 'editTitle', 'editDescription', 'editAmount', 'editCategory', 'editTransactionDate']);
        $this->dispatch('closeModal', id: 'editTransactionModal');
        $this->dispatch('showAlert', type: 'success', message: 'Transaksi berhasil diubah!');
    }

    // Prepare Edit Receipt
    public function prepareEditReceipt($id)
    {
        $transaction = Transaction::find($id);
        
        if (!$transaction || $transaction->user_id !== $this->auth->id) {
            return;
        }

        $this->editReceiptId = $transaction->id;
        $this->dispatch('showModal', id: 'editReceiptModal');
    }

    // Edit Receipt
    public function editReceipt()
    {
        $this->validate([
            'editReceiptFile' => 'required|image|max:2048',
        ]);

        $transaction = Transaction::find($this->editReceiptId);

        if (!$transaction || $transaction->user_id !== $this->auth->id) {
            return;
        }

        // Delete old receipt
        if ($transaction->receipt && Storage::disk('public')->exists($transaction->receipt)) {
            Storage::disk('public')->delete($transaction->receipt);
        }

        // Upload new receipt
        $userId = $this->auth->id;
        $dateNumber = now()->format('YmdHis');
        $extension = $this->editReceiptFile->getClientOriginalExtension();
        $filename = $userId . '_' . $dateNumber . '.' . $extension;
        $receiptPath = $this->editReceiptFile->storeAs('receipts', $filename, 'public');

        $transaction->receipt = $receiptPath;
        $transaction->save();

        $this->reset(['editReceiptId', 'editReceiptFile']);
        $this->dispatch('closeModal', id: 'editReceiptModal');
        $this->dispatch('showAlert', type: 'success', message: 'Bukti transaksi berhasil diubah!');
    }

    // Prepare Delete
    public function prepareDelete($id)
    {
        $transaction = Transaction::find($id);
        
        if (!$transaction || $transaction->user_id !== $this->auth->id) {
            return;
        }

        $this->deleteId = $transaction->id;
        $this->deleteTitle = $transaction->title;
        $this->dispatch('showModal', id: 'deleteTransactionModal');
    }

    // Delete Transaction
    public function deleteTransaction()
    {
        if ($this->deleteConfirmTitle !== $this->deleteTitle) {
            $this->addError('deleteConfirmTitle', 'Judul konfirmasi tidak sesuai.');
            return;
        }

        $transaction = Transaction::find($this->deleteId);

        if (!$transaction || $transaction->user_id !== $this->auth->id) {
            return;
        }

        // Delete receipt if exists
        if ($transaction->receipt && Storage::disk('public')->exists($transaction->receipt)) {
            Storage::disk('public')->delete($transaction->receipt);
        }

        $transaction->delete();

        $this->reset(['deleteId', 'deleteTitle', 'deleteConfirmTitle']);
        $this->dispatch('closeModal', id: 'deleteTransactionModal');
        $this->dispatch('showAlert', type: 'success', message: 'Transaksi berhasil dihapus!');
    }
}