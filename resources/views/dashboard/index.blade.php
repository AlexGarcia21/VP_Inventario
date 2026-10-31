<x-layout title="Dashboard">

    <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-vp-morado">Administración</p>
            <h1 class="text-2xl font-extrabold text-vp-oscuro sm:text-3xl">Dashboard</h1>
            <p class="mt-1 text-vp-oscuro/70">Resumen de la operación: pedidos por atender, stock crítico y lo surtido hoy.</p>
        </div>
        <a href="/almacen"
           class="inline-flex h-12 shrink-0 items-center justify-center rounded-xl border-2 border-vp-morado px-5 font-bold text-vp-morado transition hover:bg-vp-morado hover:text-white focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
            Ir al panel de Almacén
        </a>
    </header>

    {{-- ============ INDICADORES ============ --}}
    <div class="mb-8 grid gap-4 sm:grid-cols-3">
        <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-vp-lavanda/25 text-vp-morado">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </span>
            <div>
                <p class="text-sm font-semibold text-vp-oscuro/70">Órdenes pendientes</p>
                <p class="text-3xl font-extrabold text-vp-oscuro">{{ $pendingOrders->count() }}</p>
                <p class="text-xs text-vp-oscuro/60">Esperando surtirse en Almacén</p>
            </div>
        </div>

        <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $lowStockProducts->isNotEmpty() ? 'bg-amber-100 text-amber-800' : 'bg-green-50 text-green-600' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2 21h20L12 3z"/><path d="M12 10v4"/><path d="M12 17.5v.01"/></svg>
            </span>
            <div>
                <p class="text-sm font-semibold text-vp-oscuro/70">Stock crítico</p>
                <p class="text-3xl font-extrabold {{ $lowStockProducts->isNotEmpty() ? 'text-amber-700' : 'text-vp-oscuro' }}">{{ $lowStockProducts->count() }}</p>
                <p class="text-xs text-vp-oscuro/60">Insumos en su mínimo o por debajo</p>
            </div>
        </div>

        <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
            </span>
            <div>
                <p class="text-sm font-semibold text-vp-oscuro/70">Surtidas hoy</p>
                <p class="text-3xl font-extrabold text-green-700">{{ $todayOrdersCount }}</p>
                <p class="text-xs text-vp-oscuro/60">Órdenes autorizadas hoy</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-12 lg:items-start">

        {{-- ============ ÓRDENES PENDIENTES ============ --}}
        <section class="rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-7" aria-labelledby="titulo-pendientes">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
                <h2 id="titulo-pendientes" class="text-lg font-bold text-vp-oscuro">Pedidos por surtir</h2>
                <a href="/almacen" class="text-sm font-bold text-vp-morado hover:text-vp-oscuro hover:underline">Ver en Almacén</a>
            </div>

            @if($pendingOrders->isEmpty())
                <div class="px-6 py-12 text-center">
                    <span class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-green-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                    </span>
                    <p class="font-bold text-vp-oscuro">Todo al día</p>
                    <p class="mt-1 text-sm text-vp-oscuro/70">No hay órdenes pendientes en este momento.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[420px]">
                        <thead>
                            <tr class="border-b border-gray-100 bg-vp-beige/60 text-left text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">
                                <th scope="col" class="px-5 py-3 sm:px-6">Folio</th>
                                <th scope="col" class="px-4 py-3">Solicitado</th>
                                <th scope="col" class="px-5 py-3 sm:px-6">Residente</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pendingOrders as $order)
                                <tr class="transition-colors hover:bg-vp-beige/50">
                                    <td class="whitespace-nowrap px-5 py-4 font-bold text-vp-oscuro sm:px-6">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-vp-oscuro/70">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4 font-semibold text-vp-oscuro sm:px-6">{{ $order->resident->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- ============ STOCK CRÍTICO ============ --}}
        <section class="rounded-2xl border border-gray-100 bg-white shadow-sm lg:col-span-5" aria-labelledby="titulo-stock">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
                <div>
                    <h2 id="titulo-stock" class="text-lg font-bold text-vp-oscuro">Insumos con stock crítico</h2>
                    <p class="text-sm text-vp-oscuro/70">Por debajo de su mínimo configurado</p>
                </div>
                <a href="/almacen/entradas" class="shrink-0 text-sm font-bold text-vp-morado hover:text-vp-oscuro hover:underline">Registrar entrada</a>
            </div>

            @if($lowStockProducts->isEmpty())
                <div class="px-6 py-12 text-center">
                    <span class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-green-600">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                    </span>
                    <p class="font-bold text-vp-oscuro">Inventario en orden</p>
                    <p class="mt-1 text-sm text-vp-oscuro/70">Todos los insumos están por encima de su mínimo.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($lowStockProducts as $product)
                        @php
                            $out = $product->current_stock <= 0;
                            $pct = $product->min_stock > 0 ? min(100, (int) round($product->current_stock / $product->min_stock * 100)) : 0;
                        @endphp
                        <li class="px-5 py-4 sm:px-6">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-bold leading-snug text-vp-oscuro">{{ $product->name }}</p>
                                <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $out ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $out ? 'Agotado' : 'Stock bajo' }}
                                </span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100" role="presentation">
                                <div class="h-full rounded-full {{ $out ? 'bg-red-500' : 'bg-amber-500' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="mt-2 text-sm text-vp-oscuro/70">
                                Actual <strong class="{{ $out ? 'text-red-700' : 'text-vp-oscuro' }}">{{ $product->current_stock }}</strong>
                                · Mínimo <strong class="text-vp-oscuro">{{ $product->min_stock }}</strong>
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-layout>