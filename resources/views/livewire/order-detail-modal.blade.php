<div>
    @if($isOpen && $order)
        @php
            $hasShortage = $order->items->contains(fn ($item) => $item->product->current_stock < $item->requested_quantity);
            $totalUnits  = $order->items->sum('requested_quantity');
        @endphp

        {{-- Fondo: clic fuera o tecla Esc cierran el modal --}}
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-vp-oscuro/60 backdrop-blur-sm sm:items-center sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeModal()"
             wire:click.self="closeModal"
             role="dialog" aria-modal="true" aria-labelledby="titulo-orden">

            <div class="flex max-h-[92vh] w-full max-w-2xl flex-col overflow-hidden rounded-t-3xl bg-white shadow-2xl sm:rounded-3xl">

                {{-- Encabezado --}}
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-vp-morado">Detalle del pedido</p>
                        <h3 id="titulo-orden" class="text-xl font-extrabold text-vp-oscuro">Orden #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    </div>
                    <button type="button" wire:click="closeModal" aria-label="Cerrar"
                            class="-mr-2 flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-vp-oscuro/70 transition hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-vp-morado/20">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                {{-- Cuerpo --}}
                <div class="space-y-5 overflow-y-auto px-5 py-5 sm:px-6">

                    {{-- Error al autorizar --}}
                    @if (session()->has('error'))
                        <div role="alert" class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16.5v.01"/></svg>
                            <div>
                                <p class="text-sm font-bold">No se pudo completar la acción</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Datos del pedido --}}
                    <dl class="grid gap-3 rounded-xl bg-vp-beige p-4 sm:grid-cols-3">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">Residente</dt>
                            <dd class="font-bold text-vp-oscuro">{{ $order->resident->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">Solicitado</dt>
                            <dd class="font-semibold text-vp-oscuro">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">Total de unidades</dt>
                            <dd class="font-bold text-vp-oscuro">{{ $totalUnits }}</dd>
                        </div>
                    </dl>

                    {{-- Aviso de existencias insuficientes --}}
                    @if($hasShortage)
                        <div role="alert" class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2 21h20L12 3z"/><path d="M12 10v4"/><path d="M12 17.5v.01"/></svg>
                            <p class="text-sm">
                                <span class="font-bold">No hay existencias suficientes para surtir esta orden.</span>
                                Registra una entrada de proveedor o rechaza el pedido.
                            </p>
                        </div>
                    @endif

                    {{-- Insumos solicitados --}}
                    <div>
                        <h4 class="mb-3 text-sm font-bold uppercase tracking-wide text-vp-oscuro/70">Insumos solicitados</h4>
                        <ul class="space-y-2">
                            @foreach($order->items as $item)
                                @php
                                    $stock = $item->product->current_stock;
                                    $short = $stock < $item->requested_quantity;
                                @endphp
                                <li class="flex items-center justify-between gap-4 rounded-xl border p-4 {{ $short ? 'border-red-200 bg-red-50' : 'border-gray-100 bg-vp-beige/60' }}">
                                    <div class="min-w-0">
                                        <p class="font-bold leading-snug text-vp-oscuro">{{ $item->product->name }}</p>
                                        <p class="text-sm {{ $short ? 'font-semibold text-red-700' : 'text-vp-oscuro/70' }}">
                                            Disponible: {{ $stock }}@if($short) · Faltan {{ $item->requested_quantity - $stock }}@endif
                                        </p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <p class="text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">Solicita</p>
                                        <p class="text-2xl font-extrabold leading-none text-vp-morado">{{ $item->requested_quantity }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 bg-vp-beige/50 p-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-5">
                    <button type="button" wire:click="closeModal" wire:loading.attr="disabled" wire:target="approveOrder,rejectOrder"
                            class="h-12 rounded-xl px-5 font-bold text-vp-oscuro transition hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-vp-morado/20 disabled:cursor-not-allowed disabled:opacity-50">
                        Cerrar
                    </button>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        {{-- Rechazar: pide confirmación porque no se puede deshacer --}}
                        <button type="button" wire:click.prevent="rejectOrder"
                                wire:confirm="¿Rechazar este pedido? El inventario no se modificará."
                                wire:loading.attr="disabled" wire:target="approveOrder,rejectOrder"
                                class="h-12 rounded-xl border-2 border-red-300 px-5 font-bold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-200 disabled:cursor-not-allowed disabled:opacity-50">
                            Rechazar pedido
                        </button>

                        {{-- Autorizar: wire:loading.attr es solo una defensa contra el doble clic;
                             la protección real es el lockForUpdate() del backend. --}}
                        <button type="button" wire:click.prevent="approveOrder" @disabled($hasShortage)
                                wire:loading.attr="disabled" wire:target="approveOrder,rejectOrder"
                                class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-vp-morado px-6 font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-not-allowed disabled:bg-vp-gris disabled:opacity-60">
                            <svg wire:loading wire:target="approveOrder" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="approveOrder">Autorizar salida</span>
                            <span wire:loading wire:target="approveOrder">Procesando…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>