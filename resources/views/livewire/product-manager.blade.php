<div class="space-y-8">
    {{-- Mensajes Flash de Sesión --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center justify-between p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-xl shadow-sm text-sm font-semibold">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span>{{ session('message') }}</span>
            </div>
            <button @click="show = false" class="text-green-800 font-bold hover:text-green-950">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Formulario: Crear / Editar Insumo --}}
        <div class="lg:col-span-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-vp-lavanda">
                <h2 class="text-xl font-bold text-vp-oscuro mb-2 flex items-center gap-2">
                    <span>{{ $isEditing ? '✏️' : '📦' }}</span>
                    {{ $isEditing ? 'Editar Insumo' : 'Nuevo Insumo' }}
                </h2>
                <p class="text-xs text-gray-500 mb-6">
                    {{ $isEditing ? 'Modifica los datos del insumo seleccionado.' : 'Registra un nuevo artículo en el catálogo general de Villa Plata.' }}
                </p>

                <form wire:submit.prevent="store" class="space-y-4">
                    {{-- Nombre del Insumo --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nombre del Insumo *</label>
                        <input type="text" wire:model="name" placeholder="Ej. Pañales talla M, Jabón neutro..." 
                               class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                        @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Stock Inicial / Actual --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Stock Actual (Unidades) *</label>
                        <input type="number" wire:model="current_stock" min="0" 
                               class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                        @error('current_stock') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Umbral de Stock Mínimo --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Stock Mínimo (Alerta) *</label>
                        <input type="number" wire:model="min_stock" min="1" 
                               class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                        <p class="text-[11px] text-gray-400 mt-1">Disparará alerta visual cuando el stock sea menor o igual a este valor.</p>
                        @error('min_stock') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="pt-3 flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-vp-morado hover:bg-vp-oscuro text-white text-xs font-bold py-3 px-4 rounded-lg transition-colors shadow-sm">
                            {{ $isEditing ? 'Guardar Cambios ✔' : 'Registrar Insumo ✔' }}
                        </button>
                        
                        @if($isEditing)
                            <button type="button" wire:click="resetInputFields" 
                                    class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                                Cancelar
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de Insumos Registrados --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-vp-lavanda">
                {{-- Barra Superior con Buscador --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="text-xl font-bold text-vp-oscuro">Insumos Registrados</h2>
                    <div class="w-full sm:w-72">
                        <input type="text" wire:model.live="search" placeholder="🔍 Buscar por nombre..." 
                               class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg px-4 py-2 text-xs focus:ring-vp-morado focus:border-vp-morado">
                    </div>
                </div>

                {{-- Tabla Responsive --}}
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-vp-lavanda text-white">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Insumo</th>
                                <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Stock Actual</th>
                                <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Stock Mínimo</th>
                                <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-bold text-gray-800">{{ $product->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-extrabold {{ $product->current_stock <= $product->min_stock ? 'text-red-600' : 'text-gray-700' }}">
                                            {{ $product->current_stock }} unid.
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-500">
                                        {{ $product->min_stock }} unid.
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center">
                                        @if($product->current_stock <= $product->min_stock)
                                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                                Crítico
                                            </span>
                                        @else
                                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                                Óptimo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center space-x-2">
                                        <button wire:click="edit({{ $product->id }})" 
                                                class="text-xs font-bold text-vp-morado hover:text-vp-oscuro transition-colors">
                                            Editar ✏️
                                        </button>
                                        <button wire:click="delete({{ $product->id }})" 
                                                wire:confirm="¿Seguro que deseas eliminar este insumo del catálogo?"
                                                class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors">
                                            Eliminar 🗑️
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-xs">
                                        No se encontraron insumos que coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>