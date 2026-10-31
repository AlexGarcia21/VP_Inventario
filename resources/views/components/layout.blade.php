@props(['title' => 'Villa Plata'])
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Villa Plata</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-vp-beige min-h-screen text-vp-oscuro antialiased"
      style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <x-navbar />

    {{-- pb-28 en pantallas angostas deja espacio para la barra inferior fija --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 lg:pb-12">
        {{ $slot }}
    </main>
</body>
</html>