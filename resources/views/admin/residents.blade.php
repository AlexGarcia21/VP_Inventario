<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Residentes - Villa Plata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen">
    <x-navbar />

    <div class="max-w-7xl mx-auto px-4 pb-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-vp-oscuro">Padrón de Residentes</h1>
            <p class="text-vp-lavanda font-medium">Asignación por Pisos - Villa Plata</p>
        </div>

        @livewire('resident-manager')
    </div>
</body>
</html>