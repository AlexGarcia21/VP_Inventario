<x-layout title="Almacén">

    <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-vp-morado">Almacén</p>
            <h1 class="text-2xl font-extrabold text-vp-oscuro sm:text-3xl">Órdenes pendientes</h1>
            <p class="mt-1 text-vp-oscuro/70">Revisa cada pedido de enfermería y autoriza la salida de insumos.</p>
        </div>
        <a href="/almacen/entradas"
           class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-vp-morado px-5 font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Registrar entrada de proveedor
        </a>
    </header>

    {{-- ============ ALERTA DE STOCK CRÍTICO ============ --}}
    @if($lowStockProducts->isNotEmpty())
        <section class="mb-8 rounded-2xl border border-amber-200 bg-amber-50 p-5 sm:p-6" aria-labelledby="titulo-stock">
            <div class="mb-4 flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-800">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2 21h20L12 3z"/><path d="M12 10v4"/><path d="M12 17.5v.01"/></svg>
                </span>
                <div>
                    <h2 id="titulo-stock" class="text-lg font-bold text-vp-oscuro">
                        Insumos con stock crítico
                        <span class="ml-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-sm font-bold text-amber-800">{{ $lowStockProducts->count() }}</span>
                    </h2>
                    <p class="text-sm text-vp-oscuro/70">Alcanzaron o están por debajo de su mínimo configurado. Conviene reabastecerlos.</p>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($lowStockProducts as $product)
                    @php $out = $product->current_stock <= 0; @endphp
                    <div class="rounded-xl border border-amber-200 bg-white p-4">
                        <h3 class="font-bold leading-snug text-vp-oscuro">{{ $product->name }}</h3>
                        <div class="mt-3 flex items-end justify-between text-sm">
                            <div>
                                <p class="text-vp-oscuro/60">Actual</p>
                                <p class="text-lg font-extrabold {{ $out ? 'text-red-700' : 'text-amber-700' }}">{{ $product->current_stock }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-vp-oscuro/60">Mínimo</p>
                                <p class="text-lg font-bold text-vp-oscuro">{{ $product->min_stock }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ ÓRDENES PENDIENTES ============ --}}
    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm" aria-labelledby="titulo-ordenes">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
            <h2 id="titulo-ordenes" class="text-lg font-bold text-vp-oscuro">Pedidos por surtir</h2>
            <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-vp-lavanda/25 px-2 text-sm font-bold text-vp-morado">{{ $pendingOrders->count() }}</span>
        </div>

        @if($pendingOrders->isEmpty())
            <div class="px-6 py-14 text-center">
                <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-green-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                </span>
                <p class="font-bold text-vp-oscuro">Todo al día</p>
                <p class="mt-1 text-sm text-vp-oscuro/70">No hay órdenes de insumos pendientes en este momento.</p>
            </div>
        @else
            {{-- Tabla: tablet horizontal, tablet vertical y PC (md+) --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-vp-beige/60 text-left text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">
                            <th scope="col" class="px-6 py-3">Folio</th>
                            <th scope="col" class="px-6 py-3">Solicitado</th>
                            <th scope="col" class="px-6 py-3">Residente</th>
                            <th scope="col" class="px-6 py-3">Insumos</th>
                            <th scope="col" class="px-6 py-3 text-right"><span class="sr-only">Acción</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pendingOrders as $order)
                            @php $n = $order->items->count(); @endphp
                            <tr class="transition-colors hover:bg-vp-beige/50">
                                <td class="whitespace-nowrap px-6 py-4 font-bold text-vp-oscuro">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-vp-oscuro/70">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 font-semibold text-vp-oscuro">{{ $order->resident->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-vp-oscuro/70">{{ $n }} {{ $n === 1 ? 'insumo' : 'insumos' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="Livewire.dispatch('openModal', { orderId: {{ $order->id }} })"
                                            class="h-11 rounded-xl bg-vp-morado px-5 text-sm font-bold text-white transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
                                        Revisar y surtir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tarjetas: pantallas angostas (menos de md) --}}
            <ul class="divide-y divide-gray-100 md:hidden">
                @foreach($pendingOrders as $order)
                    @php $n = $order->items->count(); @endphp
                    <li class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-bold text-vp-oscuro">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                                <p class="text-sm text-vp-oscuro/70">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="rounded-full bg-vp-lavanda/25 px-3 py-1 text-xs font-bold text-vp-morado">{{ $n }} {{ $n === 1 ? 'insumo' : 'insumos' }}</span>
                        </div>
                        <p class="mt-3 font-semibold text-vp-oscuro">{{ $order->resident->name }}</p>
                        <button type="button" onclick="Livewire.dispatch('openModal', { orderId: {{ $order->id }} })"
                                class="mt-4 h-12 w-full rounded-xl bg-vp-morado font-bold text-white transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
                            Revisar y surtir
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    @livewire('order-detail-modal')

    {{-- ============ AVISO TRAS APROBAR / RECHAZAR ============ --}}
    {{-- La tabla de órdenes se renderiza una sola vez en el servidor, así que al
         aprobar o rechazar se recarga la página. El mensaje se guarda en
         sessionStorage justo antes de recargar y se muestra al volver a cargar. --}}
    <div x-data="{ toast: null, timer: null }"
         x-init="
            const saved = sessionStorage.getItem('vp-toast');
            if (saved) {
                sessionStorage.removeItem('vp-toast');
                try { toast = JSON.parse(saved); timer = setTimeout(() => toast = null, 7000); } catch (e) {}
            }"
         class="pointer-events-none fixed inset-x-4 top-20 z-[60] flex justify-end sm:inset-x-6">
        <div x-show="toast" x-transition role="status"
             class="pointer-events-auto w-full max-w-sm rounded-xl border border-gray-200 bg-white p-4 shadow-lg">
            <div class="flex items-start gap-3">
                <span x-show="toast && toast.type === 'success'" class="mt-0.5 shrink-0 text-green-600">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
                </span>
                <span x-show="toast && toast.type !== 'success'" class="mt-0.5 shrink-0 text-vp-morado">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8v.01"/></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-vp-oscuro" x-text="toast ? toast.title : ''"></p>
                    <p class="text-sm text-vp-oscuro/70" x-text="toast ? toast.text : ''"></p>
                </div>
                <button type="button" @click="toast = null" aria-label="Cerrar aviso"
                        class="-mr-1 -mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-vp-oscuro/60 hover:bg-gray-100">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const notifyAndReload = (toast) => {
                try { sessionStorage.setItem('vp-toast', JSON.stringify(toast)); } catch (e) {}
                window.location.reload();
            };

            Livewire.on('orderApproved', () => notifyAndReload({
                type: 'success',
                title: 'Orden autorizada',
                text: 'La salida se registró y el stock fue descontado correctamente.'
            }));

            Livewire.on('orderRejected', () => notifyAndReload({
                type: 'info',
                title: 'Orden rechazada',
                text: 'La solicitud fue rechazada y no se modificó el inventario.'
            }));
        });
    </script>
</x-layout>