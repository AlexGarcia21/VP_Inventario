<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    // Campos del formulario
    public $product_id = null;
    public $name = '';
    public $current_stock = 0;
    public $min_stock = 5;

    // Estado del modal/formulario
    public $isEditing = false;
    public $search = '';

    protected function rules()
    {
        return [
            'name'          => 'required|string|max:255|unique:products,name,' . $this->product_id,
            'current_stock' => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'name.required'          => 'El nombre del insumo es obligatorio.',
        'name.unique'            => 'Ya existe un insumo registrado con este nombre.',
        'current_stock.required' => 'Indica el stock inicial.',
        'current_stock.min'      => 'El stock no puede ser negativo.',
        'min_stock.required'     => 'Configura el umbral de stock mínimo.',
        'min_stock.min'          => 'El stock mínimo debe ser al menos 1.',
    ];

    public function resetInputFields()
    {
        $this->product_id = null;
        $this->name = '';
        $this->current_stock = 0;
        $this->min_stock = 5;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate();

        Product::updateOrCreate(
            ['id' => $this->product_id],
            [
                'name'          => $this->name,
                'current_stock' => $this->current_stock,
                'min_stock'     => $this->min_stock,
            ]
        );

        session()->flash('message', $this->isEditing ? '¡Insumo actualizado con éxito!' : '¡Insumo creado con éxito!');
        $this->resetInputFields();
        $this->dispatch('productSaved');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->current_stock = $product->current_stock;
        $this->min_stock = $product->min_stock;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Insumo eliminado del catálogo.');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.product-manager', compact('products'));
    }
}