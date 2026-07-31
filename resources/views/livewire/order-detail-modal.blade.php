<div>
    @if($isOpen && $order)
        {{-- Esta linea es para el fondo oscuro que aparece detrás del modal --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center transition-opacity"> 
            
            {{-- Contenedor principal que muestra la información de la orden --}}
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 z-50 overflow-hidden flex flex-col max-h-[90vh]"> 
                
                <div class="bg-vp-lavanda p-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold">
                        Detalle de Orden #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    <button wire:click="closeModal" class="text-white hover:text-red-200 font-bold text-xl transition-colors">
                        ✖
                    </button>
                </div>

                {{-- Contenedor que muestra la información de la orden y los insumos solicitados --}}
                <div class="p-6 overflow-y-auto"> 
                    <div class="mb-4 bg-vp-beige p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600"><strong>Paciente:</strong> {{ $order->resident->name }}</p>
                        <p class="text-sm text-gray-600"><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <h4 class="font-bold text-vp-oscuro mb-3 border-b pb-2">Insumos Solicitados</h4>
                    <ul class="space-y-2">
                        @foreach($order->items as $item)
                            <li class="flex justify-between items-center bg-gray-50 p-3 rounded border border-gray-100">
                                <span class="font-medium text-gray-800">{{ $item->product->name }}</span>
                                <span class="bg-vp-morado text-white text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $item->requested_quantity }} unid.
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-gray-50 p-4 border-t flex justify-end space-x-3">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-semibold transition-colors">
                        Cerrar
                    </button>
                    <button class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold transition-colors shadow-sm">
                        Autorizar Salida ✔
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>