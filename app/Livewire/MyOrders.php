<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    // El filtro queda en la URL (?estado=pending), así se conserva al recargar
    #[Url(as: 'estado', except: '')]
    public string $status = '';

    public function setStatus(string $status): void
    {
        $this->status = in_array($status, ['pending', 'approved', 'rejected'], true) ? $status : '';
        $this->resetPage();
    }

    public function render()
    {
        $userId = auth()->id();

        // Cuántos pedidos tiene el usuario en cada estado (para los contadores)
        $counts = Order::where('user_id', $userId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $orders = Order::with(['resident', 'items.product'])
            ->where('user_id', $userId)
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->orderByDesc('id')
            ->paginate(6);

        return view('livewire.my-orders', [
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}