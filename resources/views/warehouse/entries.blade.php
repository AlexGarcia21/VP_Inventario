<x-layout title="Entradas">
    <header class="mb-8">
        <a href="/almacen" class="mb-4 inline-flex items-center gap-1 text-sm font-bold text-vp-morado hover:text-vp-oscuro hover:underline">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            Volver a órdenes pendientes
        </a>
        <p class="mb-1 text-xs font-bold uppercase tracking-widest text-vp-morado">Almacén</p>
        <h1 class="text-2xl font-extrabold text-vp-oscuro sm:text-3xl">Entrada de proveedor</h1>
        <p class="mt-1 text-vp-oscuro/70">Registra los insumos recibidos para sumarlos al inventario.</p>
    </header>

    <div class="max-w-2xl">
        @livewire('inventory-entry')
    </div>
</x-layout>