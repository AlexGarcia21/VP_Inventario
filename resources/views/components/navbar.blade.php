<nav class="bg-white border-b border-gray-200 shadow-sm mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            {{-- Identidad corporativa --}}
            <div class="flex items-center gap-3">
                <span class="text-2xl">🏡</span>
                <div>
                    <span class="font-bold text-vp-oscuro text-lg leading-tight block">VILLA PLATA</span>
                    <span class="text-[10px] text-vp-lavanda font-semibold tracking-wider block uppercase">
                        {{ auth()->user()?->role ?? 'Insumos' }}
                    </span>
                </div>
            </div>

            {{-- Navegación condicional por Rol + Botón General de Salida --}}
            <div class="flex items-center space-x-2 sm:space-x-4">
                
                {{-- Pestaña Pedidos (Enfermería / Solicitante y Admin) --}}
                <a href="/" 
                   class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('/') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                    📝 Pedidos
                </a>

                {{-- Pestaña Almacén (Supervisor y Admin) --}}
                @if(in_array(auth()->user()?->role, ['supervisor', 'admin']))
                    <a href="/almacen" 
                       class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('almacen*') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                        📦 Almacén
                    </a>
                @endif

                {{-- Pestañas Catálogos (Solo Admin) --}}
                @if(auth()->user()?->role === 'admin')
                    <a href="/admin/productos" 
                       class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('admin/productos') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                        🏷️ Insumos
                    </a>
                    <a href="/admin/residentes" 
                       class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->is('admin/residentes') ? 'bg-vp-lavanda text-white' : 'text-gray-600 hover:text-vp-morado hover:bg-vp-beige' }}">
                        🧓 Residentes
                    </a>
                @endif

                {{-- BOTÓN GENERAL DE CERRAR SESIÓN (Disponible para los 3) --}}
                <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                    @csrf
                    <button type="submit" 
                            class="px-3 py-1.5 border border-red-200 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1 shadow-sm">
                        <span>🚪</span> Salir
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>