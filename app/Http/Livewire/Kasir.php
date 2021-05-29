<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product as ProductModel;
use Carbon\Carbon;
use Livewire\WithPagination;

class Kasir extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $tax = "0%";

    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $products = ProductModel::where('name', 'like', '%'.$this->search.'%')->orderBy('created_at', 'DESC')->paginate(12);

        $condition = new \Darryldecode\Cart\CartCondition([
            'name' => 'pajak',
            'type' => 'tax',
            'target' => 'total',
            'value' => $this->tax,
            'order' => 1
        ]);

        
        \Cart::session(Auth()->id())->condition($condition);
        $items = \Cart::session(Auth()->id())->getContent()->sortBy(function ($cart){
            return $cart->attributes->get('added_at');
        });

        if(\Cart::isEmpty()){
            $cartData = [];
        }else {
            foreach($items as $item){
                $cart[] = [
                    'rowId' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category,
                    'qty' => $item->quantity,
                    'pricesinggle' => $item->price,
                    'price' => $item->getPriceSum(),
                ];
            }

            $cartData = collect($cart);
        }

        $sub_total = \Cart::session(Auth()->id())->getSubTotal();
        $total = \Cart::session(Auth()->id())->getTotal();

        $newCondition = \Cart::session(Auth()->id())->getCondition('pajak');
        $pajak = $newCondition->getCalculatedValue($sub_total);

        $summary = [
            'sub_total' => $sub_total,
            'pajak' => $pajak,
            'total' => $total
        ];

        return view('livewire.kasir', [
            'products' => $products,
            'cart' => $cartData,
            'summary' => $summary
        ]);
    }

    public function addItem($id){

        $rowId = "Cart".$id;
        $cart = \Cart::session(Auth()->id())->getContent();
        $cekItemId = $cart->whereIn('id', $rowId);
        

        if($cekItemId->isNotEmpty()){

            $idProduct = substr($rowId, 4,5);
            $product = ProductModel::find($idProduct);

            if($product->qty == $cekItemId[$rowId]->quantity){
                session()->flash('error', '*Jumlah item kurang');
            } else {
                \Cart::session(Auth()->id())->update($rowId, [
                    'quantity' => [
                        'relative' => true,
                        'value' => 1
                    ]
                ]);
            }
            
        }else {
            $product = ProductModel::findOrFail($id);
            \Cart::session(Auth()->id())->add([
                'id' => "Cart".$product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => [
                    'added_at' => Carbon::now()
                ],
            ]);
        }
    }

    public function enableTax(){
        $this->tax = "+10%";
    }
    public function disableTax(){
        $this->tax = "0%";
    }

    public function tambahItem($rowId){
        
        $idProduct = substr($rowId, 4,5);
        $product = ProductModel::find($idProduct);

        $cart = \Cart::session(Auth()->id())->getContent();
        $cekItem = $cart->whereIn('id', $rowId);

        if($product->qty == $cekItem[$rowId]->quantity){
            session()->flash('error', '*Jumlah item kurang');
        } else {
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1
                ]
            ]);
        }

           
        
    }

    public function kurangItem($rowId){
        
        $idProduct = substr($rowId, 4,5);
        $product = ProductModel::find($idProduct);

        $cart = \Cart::session(Auth()->id())->getContent();
        $cekItem = $cart->whereIn('id', $rowId);

        if($cekItem[$rowId]->quantity == 1){
            $this->hapusItem($rowId);
        } else {
            \Cart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => -1
                ]
            ]);
        }
        
       
    
    }

    public function hapusItem($rowId){
        \Cart::session(Auth()->id())->remove($rowId);
    }
}
