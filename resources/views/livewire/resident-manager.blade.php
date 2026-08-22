<div class="space-y-8">
    {{-- Alertas de Sesión --}}
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
        {{-- Formulario: Crear / Editar --}}
        <div class="lg:col-span-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-vp-lavanda">
                <h2 class="text-xl font-bold text-vp-oscuro mb-2 flex items-center gap-2">
                    <span>{{ $isEditing ? '✏️' : '🧓' }}</span>
                    {{ $isEditing ? 'Editar Residente' : 'Nuevo Residente' }}
                </h2>
                <p class="text-xs text-gray-500 mb-6">
                    {{ $isEditing ? 'Actualiza los datos o cambia el piso asignado.' : 'Registra un nuevo adulto mayor en la residencia.' }}
                </p>

                <form wire:submit.prevent="store" class="space-y-4">
                    {{-- Nombre del Residente --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nombre Completo *</label>
                        <input type="text" wire:model="name" placeholder="Ej. Roberto Carlos, María Félix..." 
                               class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                        @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Asignación de Piso --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Piso Asignado *</label>
                        <select wire:model="floor_id" class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                            <option value="">Selecciona un piso...</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>
                        @error('floor_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="pt-3 flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-vp-morado hover:bg-vp-oscuro text-white text-xs font-bold py-3 px-4 rounded-lg transition-colors shadow-sm">
                            {{ $isEditing ? 'Guardar Cambios ✔' : 'Registrar Residente ✔' }}
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

        {{-- Tabla de Residentes --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-vp-lavanda">
                {{-- Filtros Superiores --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="text-xl font-bold text-vp-oscuro">Residentes Registrados</h2>
                    
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        {{-- Filtro por Piso --}}
                        <select wire:model.live="selectedFloorFilter" class="bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg px-3 py-2 text-xs focus:ring-vp-morado focus:border-vp-morado">
                            <option value="">Todos los Pisos</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>

                        {{-- Buscador por Nombre --}}
                        <input type="text" wire:model.live="search" placeholder="🔍 Buscar por nombre..." 
                               class="bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg px-4 py-2 text-xs focus:ring-vp-morado focus:border-vp-morado sm:w-56">
                    </div>
                </div>

                {{-- Tabla Responsive --}}
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-vp-lavanda text-white">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Residente</th>
                                <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Ubicación</th>
                                <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($residents as $resident)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="text-sm font-bold text-gray-800">{{ $resident->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center">
                                        <span class="bg-purple-100 text-vp-morado text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $resident->floor->name ?? 'Sin asignar' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center space-x-2">
                                        <button wire:click="edit({{ $resident->id }})" 
                                                class="text-xs font-bold text-vp-morado hover:text-vp-oscuro transition-colors">
                                            Editar ✏️
                                        </button>
                                        <button wire:click="delete({{ $resident->id }})" 
                                                wire:confirm="¿Seguro que deseas eliminar a este residente?"
                                                class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors">
                                            Eliminar 🗑️
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-xs">
                                        No se encontraron residentes registrados con esos filtros.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $residents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>