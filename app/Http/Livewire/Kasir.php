<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Product as ProductModel;
use App\Models\Transaction;
use App\Models\ProductTransaction;

use Carbon\Carbon;
use Livewire\WithPagination;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Kasir extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $tax = "0%";

    public $search;

    public $payment = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }



    public function render()
    {

        $products = ProductModel::where('name','like', '%'.$this->search.'%')->orderBy('created_at', 'DESC')->paginate(8);
        

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
        
        $idProduct = substr($rowId, 4,5);
        $product = ProductModel::find($idProduct);

        if($cekItemId->isNotEmpty()){

            

            if($product->qty == $cekItemId[$rowId]->quantity){
                session()->flash('error', 'Jumlah item kurang');
            } else {
                \Cart::session(Auth()->id())->update($rowId, [
                    'quantity' => [
                        'relative' => true,
                        'value' => 1
                    ]
                ]);
            }
            
        }else {

            if($product->qty == 0){
                session()->flash('error', 'Jumlah item kurang');
            }else{
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

        if($product->qty == $cekItem[$rowId]->quantity || $product->qty == 0){
            session()->flash('error', 'Jumlah item kurang');
        }else{
            if($product->qty == 0){
                session()->flash('error', 'Jumlah item kurang');
            }else{
                \Cart::session(Auth()->id())->update($rowId, [
                    'quantity' => [
                        'relative' => true,
                        'value' => 1
                    ]
                ]);  
            }
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

    public function handleSubmit() {
        $cartTotal = \Cart::session(Auth()->id())->getTotal();
        $bayar = $this->payment;
        $kembalian = (int) $bayar - (int) $cartTotal;


        if($kembalian >= 0){
            DB::beginTransaction();
            try {
                
                $allCart = \Cart::session(Auth()->id())->getContent();

                $filterCart = $allCart->map(function ($item) {
                    return [
                        'id' => substr($item->id, 4,5 ),
                        'quantity' => $item->quantity
                    ];
                });

                foreach ($filterCart as $cart) {
                   $product = ProductModel::find($cart['id']);

                   if ($product->qty === 0) {
                     return session()->flash('error', 'Jumlah item kosong');
                   }

                   $product->decrement('qty', $cart['quantity']);
                }

                $id = idGenerator::generate([
                    'table' => 'transcations',
                    'length' => 10,
                    'prefix' => 'CLTH-',
                    'field' => 'invoice_number'
                    ]);


                    Transaction::create([
                        'invoice_number' => $id,
                        'user_id' => Auth()->id(),
                        'pay' => $bayar,
                        'total' => $cartTotal
                    ]);

                    foreach ($filterCart as $cart) {
                        ProductTransaction::create([
                            'product_id' => $cart['id'],
                            'invoice_number' => $id,
                            'qty' => $cart['quantity']
                        ]);
                    }

                    \Cart::session(Auth()->id())->clear();
                    $this->payment = 0;

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return session()->flash('error', $th);
            }
        }
    }
}
