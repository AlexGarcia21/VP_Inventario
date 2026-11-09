<x-layout title="Almacén">

    <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-vp-morado">Almacén</p>
            <h1 class="text-2xl font-extrabold text-vp-oscuro sm:text-3xl">Órdenes pendientes</h1>
            <p class="mt-1 text-vp-oscuro/70">Revisa cada pedido de enfermería y autoriza la salida de insumos.</p>
        </div>
        <a href="/almacen/entradas"
           class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-vp-morado px-5 font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Registrar entrada de proveedor
        </a>
    </header>

    {{-- Alertas de stock y tabla de órdenes: se actualizan solas --}}
    @livewire('warehouse-panel')

    @livewire('order-detail-modal')

    {{-- ============ AVISO TRAS APROBAR / RECHAZAR ============ --}}
    <div x-data="{ toast: null, timer: null, show(t) { this.toast = t; clearTimeout(this.timer); this.timer = setTimeout(() => this.toast = null, 6000); } }"
         @vp-toast.window="show($event.detail)"
         class="pointer-events-none fixed inset-x-4 top-20 z-[60] flex justify-end sm:inset-x-6">
        <div x-show="toast" x-transition role="status"
             class="pointer-events-auto w-full max-w-sm rounded-xl border border-gray-200 bg-white p-4 shadow-lg">
            <div class="flex items-start gap-3">
                <span x-show="toast && toast.type === 'success'" class="mt-0.5 shrink-0 text-green-600">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
                </span>
                <span x-show="toast && toast.type !== 'success'" class="mt-0.5 shrink-0 text-vp-morado">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8v.01"/></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-vp-oscuro" x-text="toast ? toast.title : ''"></p>
                    <p class="text-sm text-vp-oscuro/70" x-text="toast ? toast.text : ''"></p>
                </div>
                <button type="button" @click="toast = null" aria-label="Cerrar aviso"
                        class="-mr-1 -mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-vp-oscuro/60 hover:bg-gray-100">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const notify = (detail) => window.dispatchEvent(new CustomEvent('vp-toast', { detail }));

            Livewire.on('orderApproved', () => notify({
                type: 'success',
                title: 'Orden autorizada',
                text: 'La salida se registró y el stock fue descontado correctamente.'
            }));

            Livewire.on('orderRejected', () => notify({
                type: 'info',
                title: 'Orden rechazada',
                text: 'Enfermería verá el motivo en "Mis pedidos".'
            }));
        });
    </script>
</x-layout>