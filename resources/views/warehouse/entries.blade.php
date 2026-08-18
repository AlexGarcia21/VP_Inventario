<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas de Almacén - Villa Plata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen p-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-vp-oscuro">Entradas de Almacén</h1>
                <p class="text-vp-lavanda font-medium">Recepción y Reabastecimiento de Insumos</p>
            </div>
            <a href="/almacen" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-bold rounded-lg transition-colors">
                ← Volver al Panel
            </a>
        </div>

        @livewire('inventory-entry')
    </div>
</body>
</html>