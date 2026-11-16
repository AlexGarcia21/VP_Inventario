<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InventoryTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryReports extends Component
{
    public $filter = 'daily'; // daily, weekly, monthly
    public $customDate;       // Almacenará la fecha exacta elegida

    public function render()
    {
        $query = InventoryTransaction::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_dispatched'))
            ->where('type', 'exit'); // Solo contabilizamos lo que salió

        // Aplicamos el filtro de fechas
        if ($this->filter === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->filter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($this->filter === 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->filter === 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($this->filter === 'custom' && $this->customDate) {
            // Filtro dinámico por la fecha exacta del calendario
            $query->whereDate('created_at', $this->customDate);
        }

        // Agrupamos por producto para tener el conteo total por insumo
        $reports = $query->groupBy('product_id')->get();

        return view('livewire.inventory-reports', compact('reports'));
    }
}