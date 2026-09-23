<div class="min-h-screen flex items-center justify-center bg-vp-beige px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-md p-8 border-t-4 border-vp-lavanda">
        <div class="text-center mb-6">
            <span class="text-4xl">🏡</span>
            <h2 class="text-2xl font-bold text-vp-oscuro mt-2">VILLA PLATA</h2>
            <p class="text-xs text-gray-500 uppercase tracking-wider">Acceso al Sistema de Insumos</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Correo Electrónico</label>
                <input type="email" wire:model="email" placeholder="usuario@villaplata.com" 
                       class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                @error('email') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Contraseña</label>
                <input type="password" wire:model="password" placeholder="••••••••" 
                       class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 text-sm focus:ring-vp-morado focus:border-vp-morado">
                @error('password') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-vp-morado hover:bg-vp-oscuro text-white text-sm font-bold py-3 rounded-lg transition-colors shadow-sm mt-2">
                Iniciar Sesión
            </button>
        </form>
    </div>
</div>