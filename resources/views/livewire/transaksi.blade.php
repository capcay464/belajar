<div>
<div class="card" class="mt-5 mr-5 ml-5 mb-5">
            <div class="card-body">
            <h2 class="font-weight-bold mb-3"> List Transaksi </h2>
            <button class="btn btn-success btn-sm"><i class="fas fa-print fa-1x"></i> Print</button>
            <table class="table table-bordered table-hovered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>No Invoice</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                
                @foreach($producttransactions as $index=>$producttransaction)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$producttransaction->product->name}}</td>
                        <td>{{$producttransaction->invoice_number}}</td>
                        <td>{{$producttransaction->qty}}</td>
                        <td>{{$producttransaction->created_at}}</td>
                        <td>
                        <button wire:click.prevent="addNew" class="btn btn-primary btn-sm" style="padding: 8px 15px"><i class="fas fa-eye fa-1x"></i></button>
                        <button class="btn btn-secondary btn-sm" style="padding: 8px 15px"><i class="fas fa-print fa-1x"></i></button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            </div>

            <!-- Modal -->
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>

        <table class="table table-bordered table-hovered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kasir</th>
                        <th>Bayar</th>
                        <th>Total Bayar</th>
                      
                    </tr>
                </thead>
                <tbody>
               


                </tbody>
            </table>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
         </div>
</div>
