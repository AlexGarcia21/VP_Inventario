<x-layout title="Mis pedidos">
    <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-vp-morado">Solicitudes</p>
            <h1 class="text-2xl font-extrabold text-vp-oscuro sm:text-3xl">Mis pedidos</h1>
            <p class="mt-1 text-vp-oscuro/70">Consulta si Almacén ya aprobó o rechazó cada pedido que enviaste.</p>
        </div>
        <a href="/"
           class="inline-flex h-12 shrink-0 items-center justify-center rounded-xl bg-vp-morado px-5 font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30">
            Nuevo pedido
        </a>
    </header>

    @livewire('my-orders')
</x-layout>