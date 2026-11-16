@php
    $user = auth()->user();
    $role = $user?->role;

    $roleLabel = [
        'solicitante' => 'Enfermería',
        'supervisor'  => 'Almacén',
        'admin'       => 'Administración',
    ][$role] ?? 'Villa Plata';

    // Pantalla de inicio de cada rol: a donde lleva el logo
    $home = [
        'solicitante' => '/',
        'supervisor'  => '/almacen',
        'admin'       => '/admin/dashboard',
    ][$role] ?? '/';
    $onHome   = request()->is(trim($home, '/') ?: '/');
    $logoTag  = $onHome ? 'div' : 'a';

    $links = [
        ['href' => '/admin/dashboard',  'label' => 'Dashboard',  'match' => 'admin/dashboard*',  'roles' => ['admin']],
        ['href' => '/',                 'label' => 'Pedidos',    'match' => '/',                 'roles' => ['solicitante', 'supervisor', 'admin']],
        ['href' => '/almacen',          'label' => 'Almacén',    'match' => 'almacen*',          'roles' => ['supervisor', 'admin']],
        ['href' => '/admin/productos',  'label' => 'Insumos',    'match' => 'admin/productos*',  'roles' => ['admin']],
        ['href' => '/admin/residentes', 'label' => 'Residentes', 'match' => 'admin/residentes*', 'roles' => ['admin']],
    ];
@endphp

<header class="sticky top-0 z-40 mb-8 border-b border-gray-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- Identidad: lleva al inicio del rol (si ya estás ahí, no es enlace) --}}
        <{{ $logoTag }} @unless($onHome) href="{{ $home }}" aria-label="Ir al inicio" @endunless
            class="flex shrink-0 items-center gap-3">
            <img src="{{ asset('images/isotipo-villaplata.png') }}" alt="Villa Plata" class="h-9 w-auto">
            <span class="hidden h-6 w-px bg-gray-200 md:block"></span>
            <span class="hidden text-sm font-semibold text-vp-oscuro/70 md:block">Control de insumos</span>
        </{{ $logoTag }}>

        <div class="flex min-w-0 items-center gap-2 sm:gap-4">

            {{-- Navegación según el rol --}}
            <nav aria-label="Principal" class="flex min-w-0 items-center gap-1 overflow-x-auto">
                @foreach($links as $link)
                    @continue(! in_array($role, $link['roles']))
                    @php
                        $active  = request()->is($link['match']);                      // estás dentro de esta sección
                        $current = request()->is(trim($link['href'], '/') ?: '/');     // estás exactamente en esta página
                    @endphp

                    @if($current)
                        {{-- Página actual: solo indicador, no enlace (evita recargar y perder el carrito o la edición) --}}
                        <span aria-current="page"
                              class="inline-flex h-10 shrink-0 cursor-default items-center rounded-xl bg-vp-morado/10 px-3 text-sm font-bold text-vp-morado sm:px-4">
                            {{ $link['label'] }}
                        </span>
                    @else
                        <a href="{{ $link['href'] }}" @if($active) aria-current="true" @endif
                           class="inline-flex h-10 shrink-0 items-center rounded-xl px-3 text-sm font-bold transition-colors sm:px-4
                                  {{ $active ? 'bg-vp-morado/10 text-vp-morado' : 'text-vp-oscuro hover:bg-vp-beige' }}">
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Usuario --}}
            <div class="hidden text-right leading-tight md:block">
                <p class="text-sm font-bold text-vp-oscuro">{{ $user?->name }}</p>
                <p class="text-xs text-vp-oscuro/60">{{ $roleLabel }}</p>
            </div>

            {{-- Cerrar sesión (disponible para los 3 roles) --}}
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit"
                        class="inline-flex h-10 items-center rounded-xl border border-gray-200 px-4 text-sm font-bold text-vp-oscuro transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                    Salir
                </button>
            </form>
        </div>
    </div>
</header>