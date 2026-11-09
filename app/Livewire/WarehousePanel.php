<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class WarehousePanel extends Component
{
    // Cuando el modal aprueba o rechaza una orden avisa con un evento. Un componente
    // que atiende un evento se vuelve a renderizar, así la tabla se actualiza al
    // instante, sin recargar la página.
    #[On('orderApproved')]
    public function refreshAfterApproval(): void
    {
        // No hace falta código: basta con que el evento dispare el nuevo render.
    }

    #[On('orderRejected')]
    public function refreshAfterRejection(): void
    {
        // Igual que arriba.
    }

    public function render()
    {
        // Las más antiguas primero (primeras entradas, primeras salidas)
        $pendingOrders = Order::with(['resident', 'items'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Alerta: stock actual menor o igual al mínimo de cada insumo
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')
            ->orderBy('current_stock', 'asc')
            ->get();

        return view('livewire.warehouse-panel', compact('pendingOrders', 'lowStockProducts'));
    }
}