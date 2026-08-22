<nav class="bg-white border-b border-gray-200 shadow-sm mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Identidad de la Residencia --}}
            <div class="flex items-center gap-3">
                <span class="text-2xl">🏡</span>
                <div>
                    <span class="font-bold text-vp-oscuro text-lg leading-tight block">VILLA PLATA</span>
                    <span class="text-[10px] text-vp-lavanda font-semibold tracking-wider block uppercase">Control de Insumos</span>
                </div>
            </div>

            {{-- Enlaces de Navegación --}}
            <div class="flex items-center space-x-2 sm:space-x-4">
                {{-- Solicitudes (Enfermería) --}}
                <a href="/" 
                   class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('/') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                    📝 Pedidos
                </a>

                {{-- Almacén --}}
                <a href="/almacen" 
                   class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('almacen*') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                    📦 Almacén
                </a>

                {{-- Catálogo de Insumos --}}
                <a href="/admin/productos" 
                   class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('admin/productos') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                    🏷️ Insumos
                </a>

                {{-- Padrón de Residentes --}}
                <a href="/admin/residentes" 
                   class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('admin/residentes') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                    🧓 Residentes
                </a>
            </div>
        </div>
    </div>
</nav>