<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction as TransactionModel;

class Transaksi extends Component
{
    public function render()
    {
        $transactions = TransactionModel::orderBy('created_at', 'DESC')->get();
        return view('livewire.Transaksi', [
            'transactions' => $transactions
        ]);
    }
}
