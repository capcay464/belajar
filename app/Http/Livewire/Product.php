<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product as ProductModel;
use Illuminate\Support\Facades\Storage;

class Product extends Component
{
    use WithFileUploads;

    public $name,$category,$image,$desc,$qty,$price;

    public function render()
    {
        $products = ProductModel::orderBy('created_at', 'DESC')->get();
        return view('livewire.product', [
            'products' => $products

        ]);
    }

    public function previewImage(){
        $this->validate([
            'image' => 'image|max:2048'
        ]);

    }

    public function store(){
        $this->validate([
            'name' => 'required',
            'category' => 'required',
            'image' => 'image|max:2048|required',
            'desc' => 'required',
            'qty' => 'required',
            'price' => 'required',
        ]);

        $imageName = md5($this->image.microtime().'.'.$this->image->extension());

        Storage::putFileAs(
            'public/images',
            $this->image,
            $imageName
        );

        ProductModel::create([
            'name' => $this->name,
            'category' => $this->category,
            'image' => $imageName,
            'desc' => $this->desc,
            'qty' => $this->qty,
            'price' => $this->price,
            
        ]);

        session()->flash('info','Product Created Successfully');

        $this->name = '';
        $this->category = '';
        $this->image = '';
        $this->desc = '';
        $this->qty = '';
        $this->price = '';
    }
}
