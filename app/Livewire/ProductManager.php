<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

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
            /* Rule::unique()->ignore() maneja bien tanto la creación (product_id es
            null, así que no ignora nada) como la edición (ignora el propio
            registro). La versión anterior concatenaba el ID directo en el string
            de reglas ("unique:products,name," . $this->product_id), lo que además
            dejaba la regla mal formada en modo creación, porque quedaba como
            "unique:products,name," sin ningún ID al final. */
            'name'          => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')->ignore($this->product_id),
            ],
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
        try {
            Product::findOrFail($id)->delete();
            session()->flash('message', 'Insumo eliminado del catálogo.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Ocurre cuando el producto ya tiene historial (order_items o
            // inventory_transactions) y la base de datos rechaza el borrado
            // por la restricción de llave foránea (RESTRICT por defecto).
            session()->flash('error', 'No se puede eliminar este insumo porque ya tiene movimientos u órdenes registradas.');
        }
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.product-manager', compact('products'));
    }
}