<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-vp-lavanda">
    <h2 class="text-xl font-bold text-vp-oscuro mb-2 flex items-center gap-2">
        <span>📥</span> Registrar Entrada de Proveedor
    </h2>
    <p class="text-xs text-gray-500 mb-6">
        Ingresa insumos recibidos para sumar existencias directamente al almacén general.
    </p>

    {{-- Alertas de Éxito o Error --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded shadow-sm text-sm font-semibold">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm text-sm font-semibold">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="registerEntry" class="space-y-4">
        {{-- Selector de Insumo --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Insumo Recibido *</label>
            <select wire:model="product_id" class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 focus:ring-vp-morado focus:border-vp-morado text-sm">
                <option value="">Selecciona un producto del catálogo...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} (Stock actual: {{ $product->current_stock }} unid.)
                    </option>
                @endforeach
            </select>
            @error('product_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
        </div>

        {{-- Cantidad Ingresada --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad Recibida (Unidades) *</label>
            <input type="number" wire:model="quantity" min="1" placeholder="Ej. 50" class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 focus:ring-vp-morado focus:border-vp-morado text-sm">
            @error('quantity') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
        </div>

        {{-- Notas / Factura --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Notas / Referencia de Factura (Opcional)</label>
            <input type="text" wire:model="notes" placeholder="Ej. Factura #9812 - Proveedor Farmacias" class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 focus:ring-vp-morado focus:border-vp-morado text-sm">
            @error('notes') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
        </div>

        {{-- Botón de Registro --}}
        <div class="pt-2">
            <button type="submit" class="w-full bg-vp-morado hover:bg-vp-oscuro text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-sm text-sm">
                Sumar Insumos al Inventario ✔
            </button>
        </div>
    </form>
</div>