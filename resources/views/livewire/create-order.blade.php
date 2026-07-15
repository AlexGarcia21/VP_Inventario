<div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6">
    <h1 class="text-xl font-bold mb-4">Levantar pedido de insumos</h1>

    {{-- Selector de piso --}}
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Piso</label>
        <select wire:model="selectedFloor" class="w-full border-gray-300 rounded-md">
            <option value="">-- Selecciona un piso --</option>
            @foreach ($floors as $floor)
                {{--<option value="{{ $floor->id }}">Piso {{ $floor->numero }}</option> --}}
                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Selector de residente (solo aparece si ya se eligió un piso) --}}
    @if ($selectedFloor)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Residente (opcional — déjalo vacío si el pedido es general del piso)
            </label>
            <select wire:model="selectedResident" class="w-full border-gray-300 rounded-md">
                <option value="">-- Pedido general del piso --</option>
                @foreach ($residents as $resident)
                    <option value="{{ $resident->id }}">{{ $resident->nombre }}</option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Catálogo de insumos --}}
    <div class="mb-4">
        <h2 class="text-sm font-medium text-gray-700 mb-2">Insumos disponibles</h2>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($products as $product)
                <div class="border rounded-md p-3 flex justify-between items-center">
                    <span>{{ $product->nombre }}</span>
                    <button
                        type="button"
                        wire:click="agregarAlCarrito({{ $product->id }})"
                        class="text-sm bg-blue-600 text-white px-2 py-1 rounded"
                    >
                        Agregar
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Carrito --}}
    <div class="mb-4">
        <h2 class="text-sm font-medium text-gray-700 mb-2">Carrito</h2>
        @if (empty($cart))
            <p class="text-sm text-gray-400">Aún no has agregado insumos.</p>
        @else
            <ul class="divide-y">
                @foreach ($cart as $productId => $cantidad)
                    <li class="py-2 flex justify-between items-center">
                        <span>Insumo #{{ $productId }}</span>
                        <input
                            type="number"
                            min="1"
                            wire:model="cart.{{ $productId }}"
                            class="w-20 border-gray-300 rounded-md text-sm"
                        >
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <button
        type="button"
        wire:click="guardarPedido"
        class="w-full bg-green-600 text-white py-2 rounded-md font-medium"
    >
        Enviar pedido
    </button>
</div>