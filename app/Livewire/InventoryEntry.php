<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class InventoryEntry extends Component
{
    public $product_id = '';
    public $quantity = '';
    public $notes = '';

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
        'notes'      => 'nullable|string|max:255',
    ];

    protected $messages = [
        'product_id.required' => 'Debes seleccionar un insumo.',
        'product_id.exists'   => 'El insumo seleccionado no es válido.',
        'quantity.required'   => 'Ingresa una cantidad válida.',
        'quantity.min'        => 'La cantidad mínima a ingresar es 1 unidad.',
    ];

    public function registerEntry()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // 1. Buscamos el producto y sumamos al stock físico
                $product = Product::findOrFail($this->product_id);
                $product->current_stock += $this->quantity;
                $product->save();

                // 2. Registramos el movimiento en el libro contable de inventario
                InventoryTransaction::create([
                    'product_id' => $this->product_id,
                    'user_id'    => auth()->id() ?? 1, // Usuario actual o ID por defecto
                    'type'       => 'entry',
                    'quantity'   => $this->quantity,
                    'notes'      => $this->notes,
                ]);
            });

            // 3. Limpiamos campos y mostramos mensaje de éxito
            $this->reset(['product_id', 'quantity', 'notes']);
            session()->flash('success', '¡Entrada registrada con éxito! El inventario ha sido incrementado.');

        } catch (\Throwable $e) {
            session()->flash('error', 'Ocurrió un error al registrar la entrada: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $products = Product::orderBy('name', 'asc')->get();
        return view('livewire.inventory-entry', compact('products'));
    }
}