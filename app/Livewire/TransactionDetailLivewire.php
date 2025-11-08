<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TransactionDetailLivewire extends Component
{
    use WithFileUploads;

    public $transaction;
    public $auth;
    
    // Edit Receipt
    public $editReceiptFile;

    public function mount()
    {
        $this->auth = Auth::user();
        $transaction_id = request()->route('transaction_id');
        
        $targetTransaction = Transaction::where('id', $transaction_id)
                                       ->where('user_id', $this->auth->id)
                                       ->first();
        
        if (!$targetTransaction) {
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return redirect()->route('app.transactions');
        }
        
        $this->transaction = $targetTransaction;
    }

    public function render()
    {
        return view('livewire-transaction-detail-livewire');
    }

    // Edit Receipt
    public function editReceipt()
    {
        $this->validate([
            'editReceiptFile' => 'required|image|max:2048', // 2MB Max
        ]);

        if ($this->editReceiptFile) {
            // Hapus receipt lama jika ada
            if ($this->transaction->receipt && Storage::disk('public')->exists($this->transaction->receipt)) {
                Storage::disk('public')->delete($this->transaction->receipt);
            }

            $userId = $this->auth->id;
            $dateNumber = now()->format('YmdHis');
            $extension = $this->editReceiptFile->getClientOriginalExtension();
            $filename = $userId . '_' . $dateNumber . '.' . $extension;
            $path = $this->editReceiptFile->storeAs('receipts', $filename, 'public');

            $this->transaction->receipt = $path;
            $this->transaction->save();
            
            // Refresh transaction data
            $this->transaction = Transaction::find($this->transaction->id);
        }

        $this->reset(['editReceiptFile']);
        $this->dispatch('closeModal', id: 'editReceiptModal');
        $this->dispatch('showAlert', type: 'success', message: 'Bukti transaksi berhasil diubah!');
    }

    // Delete Receipt
    public function deleteReceipt()
    {
        if ($this->transaction->receipt && Storage::disk('public')->exists($this->transaction->receipt)) {
            Storage::disk('public')->delete($this->transaction->receipt);
        }

        $this->transaction->receipt = null;
        $this->transaction->save();
        
        // Refresh transaction data
        $this->transaction = Transaction::find($this->transaction->id);

        $this->dispatch('showAlert', type: 'success', message: 'Bukti transaksi berhasil dihapus!');
    }
}