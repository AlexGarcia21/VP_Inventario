<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Residentes - Villa Plata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-vp-oscuro">Padrón de Residentes</h1>
                <p class="text-vp-lavanda font-medium">Asignación por Pisos - Villa Plata</p>
            </div>
            <a href="/admin/productos" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-bold rounded-lg transition-colors">
                Ir a Insumos →
            </a>
        </div>

        @livewire('resident-manager')
    </div>
</body>
</html>