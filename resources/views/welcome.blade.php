<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Residencia - Villa Plata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen">
    <x-navbar />

    <div class="max-w-7xl mx-auto px-4 pb-8">
        <livewire:create-order />
    </div>
</body>
</html>