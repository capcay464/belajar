<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction as TransactionModel;
use App\Models\ProductTransaction as ProductTransactionModel;
use App\Models\Product as ProductModel;

use Livewire\WithPagination;

class Transaksi extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public function addNew(){
        $this->dispatchBrowserEvent('show-form');
        
        
    }

    public function render()
    {

        $producttransactions = ProductTransactionModel::with('product')->orderBy('created_at', 'DESC')->paginate(4);
        return view('livewire.Transaksi', [
            'producttransactions' => $producttransactions
        ]);

        

       
    }

}
