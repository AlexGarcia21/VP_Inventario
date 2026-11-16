<div>
    {{-- ============ AVISOS FLOTANTES ============ --}}
    <div class="pointer-events-none fixed inset-x-4 top-20 z-[60] space-y-3 sm:left-auto sm:right-6 sm:w-96">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" role="status"
                 class="pointer-events-auto flex items-start gap-3 rounded-xl border border-green-200 bg-white p-4 shadow-lg">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
                <p class="flex-1 text-sm font-semibold text-vp-oscuro">{{ session('message') }}</p>
                <button type="button" @click="show = false" aria-label="Cerrar aviso" class="-mr-1 -mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-vp-oscuro/60 hover:bg-gray-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 7000)" role="alert"
                 class="pointer-events-auto flex items-start gap-3 rounded-xl border border-red-200 bg-white p-4 shadow-lg">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16.5v.01"/></svg>
                <p class="flex-1 text-sm font-semibold text-vp-oscuro">{{ session('error') }}</p>
                <button type="button" @click="show = false" aria-label="Cerrar aviso" class="-mr-1 -mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-vp-oscuro/60 hover:bg-gray-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-12 lg:items-start">

        {{-- ============ FORMULARIO: crear / editar ============ --}}
        <section id="formulario-residente" class="scroll-mt-24 lg:sticky lg:top-24 lg:col-span-4 lg:max-h-[calc(100vh-7rem)] lg:overflow-y-auto" aria-labelledby="titulo-form">
            <div class="rounded-2xl border bg-white p-5 shadow-sm sm:p-6 {{ $isEditing ? 'border-vp-morado ring-4 ring-vp-morado/10' : 'border-gray-100' }}">
                <div class="mb-5">
                    @if($isEditing)
                        <span class="mb-2 inline-flex rounded-full bg-vp-morado/10 px-3 py-1 text-xs font-bold text-vp-morado">Editando</span>
                    @endif
                    <h2 id="titulo-form" class="text-lg font-bold text-vp-oscuro">{{ $isEditing ? 'Editar residente' : 'Nuevo residente' }}</h2>
                    <p class="text-sm text-vp-oscuro/70">
                        {{ $isEditing ? 'Actualiza los datos o cambia el piso asignado.' : 'Registra a un nuevo residente y asígnalo a un piso.' }}
                    </p>
                </div>

                <form wire:submit.prevent="store" class="space-y-5" novalidate>

                    {{-- Nombre --}}
                    <div>
                        <label for="residente-nombre" class="mb-2 block text-sm font-bold text-vp-oscuro">Nombre completo <span class="text-vp-morado">*</span></label>
                        <input id="residente-nombre" type="text" wire:model="name" autocomplete="off"
                               placeholder="Ej. Roberto Carlos, María Félix…"
                               @error('name') aria-invalid="true" @enderror
                               class="block h-12 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('name') border-red-400 @else border-gray-200 @enderror">
                        @error('name') <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Piso --}}
                    <div>
                        <label for="residente-piso" class="mb-2 block text-sm font-bold text-vp-oscuro">Piso asignado <span class="text-vp-morado">*</span></label>
                        <select id="residente-piso" wire:model="floor_id"
                                @error('floor_id') aria-invalid="true" @enderror
                                class="block h-12 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('floor_id') border-red-400 @else border-gray-200 @enderror">
                            <option value="">Selecciona un piso…</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>
                        @error('floor_id') <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Acciones --}}
                    <div class="flex gap-3 pt-1">
                        <button type="submit" wire:loading.attr="disabled" wire:target="store"
                                class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-vp-morado font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-wait disabled:opacity-70">
                            <svg wire:loading wire:target="store" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>{{ $isEditing ? 'Guardar cambios' : 'Registrar residente' }}</span>
                        </button>

                        @if($isEditing)
                            <button type="button" wire:click="resetInputFields"
                                    class="h-12 rounded-xl border border-gray-200 px-5 font-bold text-vp-oscuro transition hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-vp-morado/20">
                                Cancelar
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        {{-- ============ LISTA DE RESIDENTES ============ --}}
        <section class="lg:col-span-8" aria-labelledby="titulo-lista">
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="flex flex-col gap-4 border-b border-gray-100 p-5 sm:p-6">
                    <div>
                        <h2 id="titulo-lista" class="text-lg font-bold text-vp-oscuro">Residentes registrados</h2>
                        <p class="text-sm text-vp-oscuro/70">{{ $residents->total() }} {{ $residents->total() === 1 ? 'residente' : 'residentes' }}{{ (filled($search) || filled($selectedFloorFilter)) ? ' con esos filtros' : '' }}</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <div class="sm:w-52">
                            <label for="filtro-piso" class="sr-only">Filtrar por piso</label>
                            <select id="filtro-piso" wire:model.live="selectedFloorFilter"
                                    class="block h-12 w-full rounded-xl border border-gray-200 bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15">
                                <option value="">Todos los pisos</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative flex-1">
                            <label for="buscar-residente" class="sr-only">Buscar residente por nombre</label>
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-vp-oscuro/50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                            <input id="buscar-residente" type="search" wire:model.live.debounce.300ms="search" autocomplete="off"
                                   placeholder="Buscar por nombre…"
                                   class="block h-12 w-full rounded-xl border border-gray-200 bg-vp-beige pl-12 pr-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px]">
                        <thead>
                            <tr class="border-b border-gray-100 bg-vp-beige/60 text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">Residente</th>
                                <th scope="col" class="px-4 py-3 text-center">Piso</th>
                                <th scope="col" class="px-5 py-3 text-right sm:px-6"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($residents as $resident)
                                @php $editing = $isEditing && $resident_id == $resident->id; @endphp
                                <tr wire:key="residente-{{ $resident->id }}" class="transition-colors {{ $editing ? 'bg-vp-lavanda/15' : 'hover:bg-vp-beige/50' }}">
                                    <td class="px-5 py-4 font-bold text-vp-oscuro sm:px-6">{{ $resident->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-center">
                                        <span class="rounded-full bg-vp-lavanda/25 px-3 py-1 text-xs font-bold text-vp-morado">
                                            {{ $resident->floor->name ?? 'Sin asignar' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- En pantallas angostas el formulario queda arriba: se sube hasta él al editar --}}
                                            <button type="button" wire:click="edit({{ $resident->id }})"
                                                    x-data @click="if (window.matchMedia('(max-width: 1023px)').matches) document.getElementById('formulario-residente').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                                    class="h-10 rounded-lg border border-vp-morado/30 px-4 text-sm font-bold text-vp-morado transition hover:bg-vp-morado hover:text-white focus:outline-none focus:ring-4 focus:ring-vp-morado/20">
                                                Editar
                                            </button>
                                            <button type="button" wire:click="delete({{ $resident->id }})"
                                                    wire:confirm="¿Seguro que deseas eliminar a este residente?"
                                                    class="h-10 rounded-lg px-3 text-sm font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-200">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-vp-oscuro/70">
                                        {{ (filled($search) || filled($selectedFloorFilter)) ? 'No encontramos residentes con esos filtros.' : 'Aún no hay residentes registrados. Agrega el primero con el formulario.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($residents->hasPages())
                    <div class="border-t border-gray-100 p-4 sm:px-6">
                        {{ $residents->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>