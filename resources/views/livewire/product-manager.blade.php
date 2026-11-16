<div>
    {{-- ============ AVISOS FLOTANTES (siempre visibles, aunque estés al fondo de la lista) ============ --}}
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
        <section id="formulario-insumo" class="scroll-mt-24 lg:sticky lg:top-24 lg:col-span-4 lg:max-h-[calc(100vh-7rem)] lg:overflow-y-auto" aria-labelledby="titulo-form">
            <div class="rounded-2xl border bg-white p-5 shadow-sm sm:p-6 {{ $isEditing ? 'border-vp-morado ring-4 ring-vp-morado/10' : 'border-gray-100' }}">
                <div class="mb-5">
                    @if($isEditing)
                        <span class="mb-2 inline-flex rounded-full bg-vp-morado/10 px-3 py-1 text-xs font-bold text-vp-morado">Editando</span>
                    @endif
                    <h2 id="titulo-form" class="text-lg font-bold text-vp-oscuro">{{ $isEditing ? 'Editar insumo' : 'Nuevo insumo' }}</h2>
                    <p class="text-sm text-vp-oscuro/70">
                        {{ $isEditing ? 'Modifica los datos del insumo seleccionado.' : 'Registra un nuevo artículo en el catálogo de Villa Plata.' }}
                    </p>
                </div>

                <form wire:submit.prevent="store" class="space-y-5" novalidate>

                    {{-- Nombre --}}
                    <div>
                        <label for="insumo-nombre" class="mb-2 block text-sm font-bold text-vp-oscuro">Nombre del insumo <span class="text-vp-morado">*</span></label>
                        <input id="insumo-nombre" type="text" wire:model="name" autocomplete="off"
                               placeholder="Ej. Pañales talla M, jabón neutro…"
                               @error('name') aria-invalid="true" @enderror
                               class="block h-12 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('name') border-red-400 @else border-gray-200 @enderror">
                        @error('name') <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Marca --}}
                    <div>
                        <label for="insumo-marca" class="mb-2 block text-sm font-bold text-vp-oscuro">Marca <span class="font-normal text-vp-oscuro/60">(opcional)</span></label>
                        <input id="insumo-marca" type="text" wire:model="brand" autocomplete="off"
                               placeholder="Ej. Huggies, Kleenex…"
                               @error('brand') aria-invalid="true" @enderror
                               class="block h-12 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('brand') border-red-400 @else border-gray-200 @enderror">
                        @error('brand') <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Stock actual y mínimo --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="insumo-stock" class="mb-2 block text-sm font-bold text-vp-oscuro">Stock actual <span class="text-vp-morado">*</span></label>
                            <input id="insumo-stock" type="number" inputmode="numeric" min="0" wire:model="current_stock"
                                   @error('current_stock') aria-invalid="true" @enderror
                                   class="block h-12 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('current_stock') border-red-400 @else border-gray-200 @enderror">
                            @error('current_stock') <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="insumo-minimo" class="mb-2 block text-sm font-bold text-vp-oscuro">Stock mínimo <span class="text-vp-morado">*</span></label>
                            <input id="insumo-minimo" type="number" inputmode="numeric" min="1" wire:model="min_stock"
                                   @error('min_stock') aria-invalid="true" @enderror
                                   class="block h-12 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('min_stock') border-red-400 @else border-gray-200 @enderror">
                            @error('min_stock') <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <p class="-mt-2 text-xs text-vp-oscuro/70">Se marca como "stock bajo" cuando el stock actual es menor o igual al mínimo.</p>

                    @if($isEditing)
                        <p class="rounded-xl bg-vp-beige p-3 text-xs text-vp-oscuro/80">
                            Ajustar el stock aquí no deja registro en el historial. Para mercancía recibida usa
                            <a href="/almacen/entradas" class="font-bold text-vp-morado underline">Entrada de proveedor</a>.
                        </p>
                    @endif

                    {{-- Acciones --}}
                    <div class="flex gap-3 pt-1">
                        <button type="submit" wire:loading.attr="disabled" wire:target="store"
                                class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-vp-morado font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-wait disabled:opacity-70">
                            <svg wire:loading wire:target="store" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>{{ $isEditing ? 'Guardar cambios' : 'Registrar insumo' }}</span>
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

        {{-- ============ LISTA DE INSUMOS ============ --}}
        <section class="lg:col-span-8" aria-labelledby="titulo-lista">
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="flex flex-col gap-4 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div>
                        <h2 id="titulo-lista" class="text-lg font-bold text-vp-oscuro">Insumos registrados</h2>
                        <p class="text-sm text-vp-oscuro/70">{{ $products->total() }} {{ $products->total() === 1 ? 'insumo' : 'insumos' }}{{ filled($search) ? ' con esa búsqueda' : ' en el catálogo' }}</p>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <label for="buscar-insumo" class="sr-only">Buscar insumo por nombre</label>
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-vp-oscuro/50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input id="buscar-insumo" type="search" wire:model.live.debounce.300ms="search" autocomplete="off"
                               placeholder="Buscar por nombre…"
                               class="block h-12 w-full rounded-xl border border-gray-200 bg-vp-beige pl-12 pr-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px]">
                        <thead>
                            <tr class="border-b border-gray-100 bg-vp-beige/60 text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">
                                <th scope="col" class="px-5 py-3 text-left sm:px-6">Insumo</th>
                                <th scope="col" class="px-4 py-3 text-right">Stock actual</th>
                                <th scope="col" class="px-4 py-3 text-right">Mínimo</th>
                                <th scope="col" class="px-4 py-3 text-center">Estado</th>
                                <th scope="col" class="px-5 py-3 text-right sm:px-6"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($products as $product)
                                @php
                                    $out     = $product->current_stock <= 0;
                                    $low     = ! $out && $product->current_stock <= $product->min_stock;
                                    $editing = $isEditing && $product_id == $product->id;
                                @endphp
                                <tr wire:key="insumo-{{ $product->id }}" class="transition-colors {{ $editing ? 'bg-vp-lavanda/15' : 'hover:bg-vp-beige/50' }}">
                                    <td class="px-5 py-4 sm:px-6">
                                        <p class="font-bold text-vp-oscuro">{{ $product->name }}</p>
                                        @if($product->brand)
                                            <p class="text-sm text-vp-oscuro/60">{{ $product->brand }}</p>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right">
                                        <span class="font-extrabold tabular-nums {{ $out ? 'text-red-700' : ($low ? 'text-amber-700' : 'text-vp-oscuro') }}">{{ $product->current_stock }}</span>
                                        <span class="text-sm text-vp-oscuro/60">unid.</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right text-vp-oscuro/70">
                                        <span class="tabular-nums">{{ $product->min_stock }}</span> <span class="text-sm">unid.</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-center">
                                        @if($out)
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">Agotado</span>
                                        @elseif($low)
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Stock bajo</span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">Óptimo</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- En pantallas angostas el formulario queda arriba: se baja/sube hasta él al editar --}}
                                            <button type="button" wire:click="edit({{ $product->id }})"
                                                    x-data @click="if (window.matchMedia('(max-width: 1023px)').matches) document.getElementById('formulario-insumo').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                                    class="h-10 rounded-lg border border-vp-morado/30 px-4 text-sm font-bold text-vp-morado transition hover:bg-vp-morado hover:text-white focus:outline-none focus:ring-4 focus:ring-vp-morado/20">
                                                Editar
                                            </button>
                                            <button type="button" wire:click="delete({{ $product->id }})"
                                                    wire:confirm="¿Seguro que deseas eliminar este insumo del catálogo?"
                                                    class="h-10 rounded-lg px-3 text-sm font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-200">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-vp-oscuro/70">
                                        {{ filled($search) ? 'No encontramos insumos con esa búsqueda.' : 'Aún no hay insumos registrados. Agrega el primero con el formulario.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="border-t border-gray-100 p-4 sm:px-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>