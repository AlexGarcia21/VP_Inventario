<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Villa Plata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen">
    <x-navbar />

    <div class="max-w-7xl mx-auto px-4 pb-8">
        <h1 class="text-3xl font-bold text-vp-oscuro mb-8">Dashboard</h1>

        {{-- MÉTRICAS RÁPIDAS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Órdenes Pendientes</p>
                <p class="text-3xl font-extrabold text-vp-oscuro mt-1">{{ $pendingOrders->count() }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Insumos con Stock Crítico</p>
                <p class="text-3xl font-extrabold text-red-600 mt-1">{{ $lowStockProducts->count() }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Órdenes Surtidas Hoy</p>
                <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $todayOrdersCount }}</p>
            </div>
        </div>

        {{-- SECCIÓN: Alertas de Stock Mínimo --}}
        @if($lowStockProducts->isNotEmpty())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xl">⚠️</span>
                    <h2 class="text-lg font-bold text-red-800">Insumos con Stock Crítico (por debajo de su mínimo configurado)</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($lowStockProducts as $product)
                        <div class="bg-white p-4 rounded-lg border border-red-200 shadow-sm">
                            <h3 class="font-bold text-gray-800 text-sm">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Existencia actual:
                                <span class="font-extrabold text-red-600">{{ $product->current_stock }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- TABLA: Órdenes Pendientes --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-vp-lavanda">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Folio</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Fecha de Solicitud</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Residente</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pendingOrders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-vp-oscuro font-medium">
                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                {{ $order->resident->name }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 font-medium">
                                No hay órdenes de insumos pendientes en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>