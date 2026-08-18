<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Almacén - Villa Plata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen p-8">
    
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-vp-oscuro">Panel de Almacén - Órdenes Pendientes</h1>
    <a href="/almacen/entradas" class="bg-vp-morado hover:bg-vp-oscuro text-white text-sm font-bold py-2.5 px-4 rounded-lg transition-colors shadow-sm">
        + Registrar Entrada de Proveedor
    </a>
        </div>

{{-- SECCIÓN: Alertas de Stock Mínimo --}}
@if($lowStockProducts->isNotEmpty())
    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-xl shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-xl">⚠️</span>
            <h2 class="text-lg font-bold text-red-800">Insumos con Stock Crítico</h2>
        </div>
        <p class="text-xs text-red-600 mb-4 font-medium">
            Los siguientes artículos han alcanzado o están por debajo de su umbral mínimo configurado.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($lowStockProducts as $product)
                <div class="bg-white p-4 rounded-lg border border-red-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold text-red-500 uppercase tracking-wider">Alerta de Existencia</span>
                        <h3 class="font-bold text-gray-800 text-sm mt-1">{{ $product->name }}</h3>
                    </div>
                    
                    <div class="mt-3 flex justify-between items-end border-t pt-2 border-gray-100 text-xs">
                        <div>
                            <p class="text-gray-500">Actual:</p>
                            <p class="font-extrabold text-red-600 text-sm">{{ $product->current_stock }} unid.</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500">Mínimo:</p>
                            <p class="font-bold text-gray-700 text-sm">{{ $product->min_stock }} unid.</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-vp-lavanda">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Folio</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Fecha de Solicitud</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Residente</th>
                        <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Artículos</th>
                        <th scope="col" class="px-6 py-4 text-center text-sm font-semibold text-white uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Iteramos sobre el arreglo de órdenes pendientes -->
                    @forelse ($pendingOrders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-vp-oscuro font-medium">
                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                <!-- Aquí usamos la relación 'resident' que trajimos con eager loading -->
                                {{ $order->resident->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <!-- Contamos cuántos items vienen en esta orden -->
                                {{ $order->items->count() }} insumos
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button onclick="Livewire.dispatch('openModal', { orderId: {{ $order->id }} })" class="bg-vp-morado hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition-colors">
                                 Revisar y Surtir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium">
                                No hay órdenes de insumos pendientes en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
 @livewire('order-detail-modal')
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('livewire:init', () => {
        // Alerta de Aprobación
        Livewire.on('orderApproved', () => {
            Swal.fire({
                title: '¡Orden Autorizada!',
                text: 'La salida se registró y el stock ha sido descontado correctamente.',
                icon: 'success',
                confirmButtonColor: '#10B981',
                confirmButtonText: 'Aceptar'
            });
        });

        // Alerta de Rechazo
        Livewire.on('orderRejected', () => {
            Swal.fire({
                title: 'Orden Rechazada',
                text: 'La solicitud ha sido rechazada y no se modifico el inventario.',
                icon: 'info',
                confirmButtonColor: '#EF4444',
                confirmButtonText: 'Entendido'
            });
        });
    });

</script>
</body>
</html>