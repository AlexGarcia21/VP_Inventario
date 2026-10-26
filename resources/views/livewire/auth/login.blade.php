<div class="min-h-screen grid lg:grid-cols-2">

    {{-- ============ PANEL DE MARCA: tablet horizontal y escritorio (lg+) ============ --}}
    <aside class="hidden lg:flex relative overflow-hidden bg-white border-r border-gray-100 items-center justify-center p-12 xl:p-16">
        <div aria-hidden="true" class="absolute -top-28 -left-28 w-96 h-96 rounded-full bg-vp-lavanda/15"></div>
        <div aria-hidden="true" class="absolute -bottom-32 -right-24 w-[28rem] h-[28rem] rounded-full bg-vp-lavanda/10"></div>

        <div class="relative text-center">
            <img src="{{ asset('images/logo-villaplata.png') }}" alt="Villa Plata: Donde los años cobran vida"
                 class="w-80 xl:w-[26rem] max-w-full h-auto mx-auto">
            <p class="mt-10 text-sm font-semibold uppercase tracking-widest text-vp-morado">Control de insumos</p>
        </div>
    </aside>

    {{-- ============ FORMULARIO ============ --}}
    <main class="flex items-center justify-center px-5 py-10 sm:px-8">
        <div class="w-full max-w-md">

            {{-- Logo compacto: solo cuando el panel lateral no se muestra (tablet vertical y móvil) --}}
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('images/logo-villaplata.png') }}" alt="Villa Plata: Donde los años cobran vida"
                     class="w-64 max-w-full h-auto mx-auto">
                <p class="mt-4 text-xs font-semibold uppercase tracking-widest text-vp-morado">Control de insumos</p>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10">
                <h1 class="text-2xl font-extrabold text-vp-oscuro">Iniciar sesión</h1>
                <p class="mt-1 text-sm text-vp-oscuro/70">Ingresa con tu cuenta para continuar.</p>

                <form wire:submit.prevent="login" class="mt-8 space-y-5" novalidate>

                    {{-- Correo --}}
                    <div>
                        <label for="email" class="block text-sm font-bold text-vp-oscuro mb-2">Correo electrónico</label>
                        <input id="email" type="email" wire:model="email"
                               autocomplete="username" inputmode="email" autofocus
                               placeholder="usuario@villaplata.com"
                               @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                               class="block w-full h-14 rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro placeholder:text-vp-gris focus:outline-none focus:ring-4 focus:ring-vp-morado/15 focus:border-vp-morado transition @error('email') border-red-400 @else border-gray-200 @enderror">
                        @error('email')
                            <p id="email-error" role="alert" class="mt-2 flex items-center gap-1.5 text-sm font-medium text-red-600">
                                <span aria-hidden="true">⚠️</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Contraseña, con botón para mostrar/ocultar --}}
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-bold text-vp-oscuro mb-2">Contraseña</label>
                        <div class="relative">
                            <input id="password" type="password" :type="show ? 'text' : 'password'" wire:model="password"
                                   autocomplete="current-password"
                                   placeholder="Tu contraseña"
                                   @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                                   class="block w-full h-14 rounded-xl border bg-vp-beige pl-4 pr-24 text-base text-vp-oscuro placeholder:text-vp-gris focus:outline-none focus:ring-4 focus:ring-vp-morado/15 focus:border-vp-morado transition @error('password') border-red-400 @else border-gray-200 @enderror">
                            <button type="button" @click="show = !show" :aria-pressed="show"
                                    class="absolute inset-y-0 right-0 px-4 text-sm font-bold text-vp-morado hover:text-vp-oscuro focus:outline-none focus:underline"
                                    x-text="show ? 'Ocultar' : 'Mostrar'">Mostrar</button>
                        </div>
                        @error('password')
                            <p id="password-error" role="alert" class="mt-2 flex items-center gap-1.5 text-sm font-medium text-red-600">
                                <span aria-hidden="true">⚠️</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Botón con estado de carga --}}
                    <button type="submit" wire:loading.attr="disabled" wire:target="login"
                            class="w-full h-14 flex items-center justify-center gap-2 rounded-xl bg-vp-morado text-white text-base font-bold shadow-sm hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 active:scale-[0.99] transition disabled:opacity-70 disabled:cursor-wait">
                        <svg wire:loading wire:target="login" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="login">Iniciar sesión</span>
                        <span wire:loading wire:target="login">Verificando…</span>
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-vp-oscuro/70">
                ¿Problemas para entrar? Contacta al administrador del sistema.
            </p>
        </div>
    </main>
</div>