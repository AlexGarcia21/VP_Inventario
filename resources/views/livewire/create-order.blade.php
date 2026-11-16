@php
    $floorModel    = $selectedFloor ? collect($floors)->firstWhere('id', $selectedFloor) : null;
    $residentModel = $selectedResident ? collect($residents)->firstWhere('id', $selectedResident) : null;
    $totalItems    = collect($cart)->sum('quantity');
    $canSubmit     = $totalItems > 0 && $residentModel;
    $filtering     = filled($search) || $onlyAvailable;
@endphp

<div>
    <header class="mb-8">
        <p class="mb-1 text-xs font-bold uppercase tracking-widest text-vp-morado">Solicitud de insumos</p>
        <h1 class="text-2xl font-extrabold text-vp-oscuro sm:text-3xl">Nuevo pedido a almacén</h1>
        <p class="mt-1 text-vp-oscuro/70">Elige al residente, agrega los insumos que necesita y envía el pedido.</p>
    </header>

    <div class="grid gap-6 lg:grid-cols-12 lg:items-start">

        {{-- ============ COLUMNA PRINCIPAL: pasos 1 y 2 ============ --}}
        <div class="space-y-6 lg:col-span-8">

            {{-- PASO 1: Residente --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="paso-residente">
                <div class="mb-5 flex items-center gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-vp-morado text-sm font-bold text-white">
                        @if($residentModel)
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                        @else
                            1
                        @endif
                    </span>
                    <div>
                        <h2 id="paso-residente" class="text-lg font-bold leading-tight text-vp-oscuro">Residente</h2>
                        <p class="text-sm text-vp-oscuro/70">¿Para quién es el pedido?</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="piso" class="mb-2 block text-sm font-bold text-vp-oscuro">Piso</label>
                        <select id="piso" wire:model.live="selectedFloor"
                                class="block h-14 w-full rounded-xl border border-gray-200 bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15">
                            <option value="">Elige un piso…</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="residente" class="mb-2 block text-sm font-bold text-vp-oscuro">Residente</label>
                        <select id="residente" wire:model.live="selectedResident" @disabled(empty($selectedFloor))
                                class="block h-14 w-full rounded-xl border border-gray-200 bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 disabled:cursor-not-allowed disabled:opacity-60">
                            <option value="">{{ empty($selectedFloor) ? 'Primero elige un piso' : 'Elige un residente…' }}</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($selectedFloor && count($residents) === 0)
                    <p class="mt-3 text-sm text-vp-oscuro/70">Este piso no tiene residentes registrados.</p>
                @endif
            </section>

            {{-- PASO 2: Insumos --}}
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="paso-insumos">
                <div class="mb-5 flex items-center gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-vp-morado text-sm font-bold text-white">
                        @if($totalItems > 0)
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                        @else
                            2
                        @endif
                    </span>
                    <div>
                        <h2 id="paso-insumos" class="text-lg font-bold leading-tight text-vp-oscuro">Insumos</h2>
                        <p class="text-sm text-vp-oscuro/70">Toca "Agregar" y ajusta la cantidad con + y −.</p>
                    </div>
                </div>

                {{-- Búsqueda y filtro --}}
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <label for="buscar" class="sr-only">Buscar insumo</label>
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-vp-oscuro/50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input id="buscar" type="search" wire:model.live.debounce.300ms="search"
                               placeholder="Buscar por nombre o marca…" autocomplete="off" enterkeyhint="search"
                               class="block h-14 w-full rounded-xl border border-gray-200 bg-vp-beige pl-12 pr-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15">
                    </div>

                    <label class="inline-flex h-14 shrink-0 cursor-pointer select-none items-center gap-3 rounded-xl border border-gray-200 bg-vp-beige px-4 text-sm font-bold text-vp-oscuro">
                        <input type="checkbox" wire:model.live="onlyAvailable" class="h-5 w-5 rounded accent-vp-morado">
                        Solo disponibles
                    </label>
                </div>

                @if($filtering)
                    <div class="mb-4 flex items-center justify-between gap-3 text-sm" aria-live="polite">
                        <p class="text-vp-oscuro/70">Mostrando {{ $products->count() }} de {{ $totalProducts }} insumos</p>
                        <button type="button" wire:click="clearFilters" class="font-bold text-vp-morado hover:text-vp-oscuro hover:underline">Limpiar filtros</button>
                    </div>
                @endif

                {{-- Aviso de stock insuficiente --}}
                @if (session()->has('cart_error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" role="alert"
                         class="mb-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2 21h20L12 3z"/><path d="M12 10v4"/><path d="M12 17.5v.01"/></svg>
                        <p class="text-sm font-semibold">{{ session('cart_error') }}</p>
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($products as $product)
                        @php
                            $inCart  = $cart[$product->id]['quantity'] ?? 0;
                            $out     = $product->current_stock <= 0;
                            $atLimit = $inCart >= $product->current_stock;
                            $low     = ! $out && $product->current_stock <= $product->min_stock;
                        @endphp

                        <article wire:key="product-{{ $product->id }}"
                                 class="flex flex-col rounded-2xl border-2 p-4 transition {{ $inCart > 0 ? 'border-vp-morado bg-vp-lavanda/10' : 'border-gray-100 bg-vp-beige/60' }}">
                            <div class="flex items-start gap-3">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-vp-lavanda/25 text-lg font-extrabold text-vp-morado" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-bold leading-snug text-vp-oscuro">{{ $product->name }}</h3>
                                    @if($product->brand)
                                        <p class="text-sm text-vp-oscuro/60">{{ $product->brand }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
                                <span class="text-vp-oscuro/70">Disponible: <strong class="text-vp-oscuro">{{ $product->current_stock }}</strong></span>
                                @if($out)
                                    <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-700">Agotado</span>
                                @elseif($low)
                                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">Stock bajo</span>
                                @endif
                            </div>

                            <div class="mt-auto pt-4">
                                @if($inCart === 0)
                                    <button type="button" wire:click="addToCart({{ $product->id }})" @disabled($out)
                                            class="h-12 w-full rounded-xl bg-vp-morado font-bold text-white transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-not-allowed disabled:opacity-50">
                                        {{ $out ? 'Sin existencias' : 'Agregar' }}
                                    </button>
                                @else
                                    <div class="flex items-center justify-between gap-2">
                                        <button type="button" wire:click="decrementQuantity({{ $product->id }})"
                                                aria-label="Quitar una unidad de {{ $product->name }}"
                                                class="h-12 w-12 rounded-xl border-2 border-vp-morado text-2xl font-bold leading-none text-vp-morado transition hover:bg-vp-morado hover:text-white focus:outline-none focus:ring-4 focus:ring-vp-morado/30">−</button>

                                        <span class="min-w-[3rem] text-center text-xl font-extrabold text-vp-oscuro" aria-live="polite">{{ $inCart }}</span>

                                        <button type="button" wire:click="addToCart({{ $product->id }})" @disabled($atLimit)
                                                aria-label="Agregar una unidad de {{ $product->name }}"
                                                class="h-12 w-12 rounded-xl bg-vp-morado text-2xl font-bold leading-none text-white transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-not-allowed disabled:opacity-40">+</button>
                                    </div>
                                    @if($atLimit)
                                        <p class="mt-2 text-center text-xs font-semibold text-amber-700">Llegaste al máximo disponible</p>
                                    @endif
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-vp-gris p-8 text-center">
                            @if($totalProducts === 0)
                                <p class="text-sm text-vp-oscuro/70">Aún no hay insumos registrados en el catálogo.</p>
                            @else
                                <p class="font-semibold text-vp-oscuro">No encontramos insumos con esos filtros.</p>
                                <button type="button" wire:click="clearFilters"
                                        class="mt-3 h-11 rounded-xl border-2 border-vp-morado px-5 text-sm font-bold text-vp-morado transition hover:bg-vp-morado hover:text-white">
                                    Limpiar filtros
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- ============ RESUMEN: fijo a la derecha en lg+, al final en pantallas angostas ============ --}}
        <aside id="resumen" class="scroll-mt-24 lg:sticky lg:top-24 lg:col-span-4" aria-labelledby="titulo-resumen">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h2 id="titulo-resumen" class="text-lg font-bold text-vp-oscuro">Resumen del pedido</h2>
                    <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-vp-lavanda/25 px-2 text-sm font-bold text-vp-morado">{{ $totalItems }}</span>
                </div>

                {{-- Confirmación de envío --}}
                @if (session()->has('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" role="status"
                         class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Residente elegido --}}
                @if($residentModel)
                    <div class="mb-5 rounded-xl bg-vp-beige p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">Pedido para</p>
                        <p class="font-bold text-vp-oscuro">{{ $residentModel->name }}</p>
                        @if($floorModel)
                            <p class="text-sm text-vp-oscuro/70">{{ $floorModel->name }}</p>
                        @endif
                    </div>
                @else
                    <div class="mb-5 rounded-xl border border-dashed border-vp-gris p-4 text-sm text-vp-oscuro/70">
                        Elige un residente en el paso 1.
                    </div>
                @endif

                {{-- Insumos del pedido --}}
                @if(empty($cart))
                    <div class="rounded-xl border border-dashed border-vp-gris p-6 text-center text-sm text-vp-oscuro/70">
                        Aún no has agregado insumos.
                    </div>
                @else
                    <ul class="max-h-[40vh] divide-y divide-gray-100 overflow-y-auto">
                        @foreach($cart as $id => $item)
                            <li wire:key="cart-{{ $id }}" class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-vp-oscuro">{{ $item['name'] }}</p>
                                    <p class="text-sm text-vp-oscuro/70">Cantidad: {{ $item['quantity'] }}</p>
                                </div>
                                <button type="button" wire:click="removeFromCart({{ $id }})"
                                        class="h-10 shrink-0 rounded-lg px-3 text-sm font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-200">
                                    Quitar
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4 text-sm">
                    <span class="text-vp-oscuro/70">Total de insumos</span>
                    <span class="text-lg font-extrabold text-vp-oscuro">{{ $totalItems }}</span>
                </div>

                {{-- Enviar --}}
                <button type="button" wire:click="submitOrder" wire:loading.attr="disabled" wire:target="submitOrder" @disabled(! $canSubmit)
                        class="mt-4 flex h-14 w-full items-center justify-center gap-2 rounded-xl bg-vp-morado text-base font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-not-allowed disabled:bg-vp-gris disabled:opacity-70">
                    <svg wire:loading wire:target="submitOrder" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="submitOrder">Enviar pedido a almacén</span>
                    <span wire:loading wire:target="submitOrder">Enviando…</span>
                </button>

                @unless($canSubmit)
                    <p class="mt-3 text-center text-sm text-vp-oscuro/70">
                        {{ ! $residentModel ? 'Elige un residente para continuar.' : 'Agrega al menos un insumo.' }}
                    </p>
                @endunless
            </div>
        </aside>
    </div>

    {{-- Barra inferior para tablet vertical y móvil: lleva directo al resumen --}}
    @if($totalItems > 0)
        <div class="fixed inset-x-0 bottom-0 z-30 border-t border-gray-200 bg-white/95 px-4 py-3 shadow-[0_-4px_12px_rgba(0,0,0,0.06)] backdrop-blur lg:hidden">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-bold text-vp-oscuro">{{ $totalItems }} {{ $totalItems === 1 ? 'insumo' : 'insumos' }}</p>
                    <p class="truncate text-sm text-vp-oscuro/70">{{ $residentModel ? $residentModel->name : 'Falta elegir residente' }}</p>
                </div>
                <a href="#resumen"
                   class="inline-flex h-12 shrink-0 items-center rounded-xl bg-vp-morado px-6 font-bold text-white transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
                    Revisar pedido
                </a>
            </div>
        </div>
    @endif
</div>