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
        <h1 class="text-3xl font-bold text-vp-oscuro mb-8">Panel de Almacén - Órdenes Pendientes</h1>

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
                                <button class="bg-vp-morado hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition-colors">
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

</body>
</html>