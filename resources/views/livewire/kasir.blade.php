<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="font-weight-bold">Product List</h2>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{asset('storage/images/'.$product->image)}}" alt="Product" class="img-fluid">
                                </div>
                                <div class="card-footer">
                                    <h6 class="text-center font-weight-bold">{{$product->name}}</h6>
                                    <button wire:click="addItem({{$product->id}})" class="btn btn-primary btn-sm btn-block">Tambahkan Barang</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
         </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h2 class="font-weight-bold">Cart</h2>
                    <table class="table table-sm table-bordered  table-hovered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Jumlah</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cart as $index=>$cart)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td >{{$cart['name']}}</td>
                                    <td>{{$cart['qty']}}
                                    <button wire:click="hapusItem('{{$cart['rowId']}}')" class="btn btn-danger btn-sm" style="float: right">x</button>
                                    <button wire:click="kurangItem('{{$cart['rowId']}}')" class="btn btn-warning btn-sm mr-1 ml-auto" style="float: right">-</button>
                                    <button wire:click="tambahItem('{{$cart['rowId']}}')" class="btn btn-success btn-sm mr-1 ml-auto" style="float: right">+</button>
                                    
                                     </td>
                                    <td>{{$cart['price']}}</td>
                                </tr>
                            @empty
                                <td colspan="3"><h6 class="text-center">Empty Cart</h6></td>    
                            @endforelse
                        </tbody>
                    </table>
            </div>
            <div class="card-footer">
                <h6 class="text-danger font-weight-bold">@if(session()->has('error'))
                    {{session('error')}}
                    @endif
                    </h6>
                </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="font-weight-bold">Cart Summary</h4>
                    <h5 class="font-weight-semibold">Sub Total : {{$summary['sub_total']}}</h5>
                    <h5 class="font-weight-semibold">Pajak (10%) : {{$summary['pajak']}}</h5>
                    <h5 class="font-weight-semibold">Total : {{$summary['total']}}</h5>
                    <div>
                        <button wire:click="enableTax" class="btn btn-primary btn-block">Dengan Pajak</button>
                        <button wire:click="disableTax" class="btn btn-danger btn-block">Hapus Pajak</button>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-success active btn-block">Simpan Transaksi</button>
                        
                    </div>
                </div>
                
            </div>
         </div>
    </div>
</div>
