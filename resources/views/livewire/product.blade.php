<div>
   <div class = "row">
         <div class="col-md-8">
         
         
            <div class="card">
            <div class="card-body">
            <h2 class="font-weight-bold mb-3">List Produk</h2>
           
            <table class="table table-bordered table-hovered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th width="10%">Image</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $index=>$product)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->category}}</td>
                        
                        <td><img src="{{ asset('storage/images/'.$product->image)}}" alt="product image" class="img-fluid"></td>
                        <td>{{$product->desc}}</td>
                        <td>{{$product->qty}}</td>
                        <td>{{$product->price}}</td>
                        <td>
                        <button
                         onclick="return confirm('Apakah Yakin Ingin Menghapus Data?') || event.stopImmediatePropagation()"
                         wire:click="delete({{ $product->id }})" class="btn btn-danger btn-sm" style="padding: 8px 15px"><i class="fas fa-trash fa-1x"></i></button>
                        
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
         </div>

         </div>
         <div class="col-md-4">
         
         <div class="card">
            <div class="card-body">
            <h2 class="font-weight-bold mb-3">Create Product</h2>
            <form wire:submit.prevent="store">
                <div class="form-group">
                    <label>Product Name</label>
                    <input wire:model="name" type="text" class="form-control">
                    @error('name') <small class="text-danger">{{$message}}</small>@enderror
                </div>
                <div>
                    <label>Product Category</label>
                    <input wire:model="category" type="text" class="form-control">
                    @error('category') <small class="text-danger">{{$message}}</small>@enderror
                </div>

                <div>
                    <label>Description</label>
                    <textarea wire:model="desc" class="form-control"></textarea>
                    @error('desc') <small class="text-danger">{{$message}}</small>@enderror
                </div>

                <div>
                    <label>Qty</label>
                    <input wire:model="qty" type="number" class="form-control">
                    @error('qty') <small class="text-danger">{{$message}}</small>@enderror
                </div>

                <div>
                    <label>Price</label>
                    <input wire:model="price" type="number" class="form-control">
                    @error('price') <small class="text-danger">{{$message}}</small>@enderror
                </div>


                <div class="form-group">
                    <label>Product Image</label>
                            <div class="custom-file">
                                <input wire:model="image" type="file" class="custom-file-input" id="customFile">
                                <label for="customFile" class='custom-file-label'>Choose Image</label>
                                @error('image') <small class="text-danger">{{$message}}</small>@enderror
                            </div>
                            @if($image)
                                <label class="mt-2">Image Preview:</label>
                                <img src="{{$image->temporaryUrl()}}" class="img-fluid" alt="Preview Image">
                            @endif
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Submit Product</button>
                </div>
            </form>
            </div>
         </div>

       </div>
   </div>
</div>
