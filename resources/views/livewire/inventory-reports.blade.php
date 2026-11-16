<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-vp-lavanda h-[450px] overflow-y-auto">
    
    {{-- Cabecera y Controles de Rango de Tiempo --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-vp-oscuro flex items-center gap-2">
            <span>📊</span> Reporte de Despachos
        </h2>
        
        <div class="flex items-center gap-2">
            <select wire:model.live="filter" class="bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-2 focus:ring-vp-morado font-medium text-sm">
                <option value="daily">Hoy</option>
                <option value="yesterday">Ayer</option>
                <option value="weekly">Esta Semana</option>
                <option value="monthly">Este Mes</option>
                <option value="custom">Fecha específica...</option>
            </select>

            {{-- Input de Fecha (Solo se muestra si elige "custom") --}}
            @if($filter === 'custom')
                <input type="date" wire:model.live="customDate" class="bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-2 focus:ring-vp-morado font-medium text-sm transition-all">
            @endif
        </div>
    </div>

    {{-- Resultados --}}
    @if($reports->isEmpty())
        <div class="p-4 bg-gray-50 text-center rounded-lg text-gray-500 text-sm border border-gray-100">
            No hay salidas registradas en la fecha seleccionada.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($reports as $row)
                <div class="bg-vp-beige p-4 rounded-lg border border-gray-200 flex justify-between items-center shadow-sm">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase">Insumo</p>
                        <p class="font-bold text-vp-oscuro">{{ $row->product->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Salieron</p>
                        <p class="text-xl font-extrabold text-vp-morado">{{ $row->total_dispatched }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>