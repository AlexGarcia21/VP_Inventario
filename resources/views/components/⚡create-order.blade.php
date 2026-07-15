{{-- 
    COMPONENTE: Crear Nueva Solicitud de Insumos
    
    Descripción:
    Este componente Livewire permite a los usuarios crear nuevas solicitudes de insumos (supplies).
    El flujo es de 3 pasos:
    1. Seleccionar el piso de la residencia
    2. Seleccionar al residente que solicita los insumos
    3. Seleccionar los productos deseados y las cantidades
    
    Variables Livewire esperadas:
    - $floors: Colección de pisos disponibles
    - $residents: Residentes del piso seleccionado
    - $products: Catálogo de productos disponibles
    - $selectedFloor: ID del piso seleccionado (reactivo)
    - $selectedResident: ID del residente seleccionado (reactivo)
--}}

<div class="p-6 max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Nueva Solicitud de Insumos</h2>

    {{-- PASO 1: Selección del Piso --}}
    <div class="mb-8 bg-white p-4 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">1. Selecciona el Piso</h3>
        
        {{-- 
            Select reactivo que dispara la carga de residentes al cambiar
            wire:model.live: actualiza el modelo en tiempo real
        --}}
        <select wire:model.live="selectedFloor" class="border border-gray-300 p-2 rounded w-full md:w-1/3 focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Elige un piso --</option>
            @foreach($floors as $floor)
                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- PASO 2: Selección del Residente --}}
    {{-- 
        Solo se muestra si un piso ha sido seleccionado (@if($selectedFloor))
        Muestra los residentes del piso seleccionado
    --}}
    @if($selectedFloor)
    <div class="mb-8 bg-white p-4 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">2. Selecciona al Residente</h3>
        
        {{-- Validar que existan residentes en el piso seleccionado --}}
        @if(count($residents) > 0)
            {{-- 
                Grid de botones para seleccionar residente
                Cada botón:
                - Al hacer clic actualiza selectedResident via wire:click
                - Cambia color según selección (azul si seleccionado, gris si no)
            --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($residents as $resident)
                    <button
                        wire:click="$set('selectedResident', {{ $resident->id }})"
                        class="p-4 border rounded-lg shadow-sm transition-colors duration-200 font-medium
                        {{ $selectedResident == $resident->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-50 text-gray-700 hover:bg-blue-100' }}"
                    >
                        {{ $resident->name }}
                    </button>
                @endforeach
            </div>
        @else
            {{-- Mensaje de error si no hay residentes --}}
            <p class="text-red-500 font-medium">No hay residentes registrados en este piso.</p>
        @endif
    </div>
    @endif

    {{-- PASO 3: Catálogo de Insumos y Solicitud --}}
    {{-- 
        Solo se muestra si un residente ha sido seleccionado
        Muestra una tabla con todos los productos disponibles
    --}}
    @if($selectedResident)
    <div class="mb-8 bg-white p-4 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">3. Catálogo de Insumos</h3>
        
        {{-- Tabla responsiva de productos --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                {{-- Encabezados de la tabla --}}
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 font-semibold text-gray-700">Insumo</th>
                        <th class="p-3 font-semibold text-gray-700">Marca</th>
                        <th class="p-3 font-semibold text-gray-700 text-center">Stock Actual</th>
                        <th class="p-3 font-semibold text-gray-700 text-center">Cantidad a Pedir</th>
                    </tr>
                </thead>
                
                {{-- Cuerpo de la tabla: iterar sobre productos disponibles --}}
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            {{-- Nombre del producto --}}
                            <td class="p-3">{{ $product->name }}</td>
                            
                            {{-- Marca del producto --}}
                            <td class="p-3 text-gray-500">{{ $product->brand }}</td>
                            
                            {{-- Stock actual con estilo visual (badge verde) --}}
                            <td class="p-3 text-center">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-semibold text-sm">
                                    {{ $product->current_stock }}
                                </span>
                            </td>
                            
                            {{-- 
                                Input para cantidad a pedir:
                                - min="0": no permite números negativos
                                - max="{{ $product->current_stock }}": no permite pedir más que el stock disponible
                                - type="number": solo acepta números
                            --}}
                            <td class="p-3 text-center">
                                <input type="number" min="0" max="{{ $product->current_stock }}" class="border border-gray-300 p-1 w-20 rounded text-center focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Botón para enviar solicitud --}}
        <div class="mt-6 flex justify-end">
            {{-- 
                Botón de envío:
                TODO: Necesita wire:click conectado a la acción submitOrder() en el componente Livewire
                TODO: Agregar validación para verificar que al menos un producto fue seleccionado
            --}}
            <button class="bg-blue-600 text-white px-8 py-3 rounded-lg shadow-md font-bold hover:bg-blue-700 transition-colors">
                Enviar Solicitud a Almacén
            </button>
        </div>
    </div>
    @endif
</div>