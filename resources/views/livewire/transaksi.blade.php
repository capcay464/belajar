<div>
<div class="card" class="mt-5 mr-5 ml-5 mb-5">
            <div class="card-body">
            <h2 class="font-weight-bold mb-3"> List Transaksi </h2>
           
            <table class="table table-bordered table-hovered table-striped">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>User ID</th>
                        <th>Pay</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                
                @foreach($transactions as $index=>$transaction)
                    <tr>
                        <td>{{$transaction->invoice_number}}</td>
                        <td>{{$transaction->user_id}}</td>
                        <td>{{$transaction->pay}}</td>
                        <td>{{$transaction->total}}</td>
                        <td>{{$transaction->created_at}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            </div>
         </div>
</div>
