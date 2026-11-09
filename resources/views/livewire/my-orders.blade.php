@php
    $filters = [
        ''         => ['label' => 'Todos',      'count' => $counts->sum()],
        'pending'  => ['label' => 'Pendientes', 'count' => $counts->get('pending', 0)],
        'approved' => ['label' => 'Aprobados',  'count' => $counts->get('approved', 0)],
        'rejected' => ['label' => 'Rechazados', 'count' => $counts->get('rejected', 0)],
    ];
    $badges = [
        'pending'  => ['label' => 'Pendiente', 'class' => 'bg-vp-lavanda/25 text-vp-morado'],
        'approved' => ['label' => 'Aprobado',  'class' => 'bg-green-100 text-green-700'],
        'rejected' => ['label' => 'Rechazado', 'class' => 'bg-red-100 text-red-700'],
    ];
@endphp

{{-- wire:poll.20s: cada 20 segundos revisa si Almacén ya respondió algún pedido --}}
<div wire:poll.20s>

    {{-- Filtros por estado --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-2">
            @foreach($filters as $key => $filter)
                @php $active = $status === (string) $key; @endphp
                <button type="button" wire:click="setStatus('{{ $key }}')" aria-pressed="{{ $active ? 'true' : 'false' }}"
                        class="inline-flex h-11 items-center gap-2 rounded-xl border px-4 text-sm font-bold transition focus:outline-none focus:ring-4 focus:ring-vp-morado/20
                               {{ $active ? 'border-vp-morado bg-vp-morado text-white' : 'border-gray-200 bg-white text-vp-oscuro hover:bg-vp-beige' }}">
                    {{ $filter['label'] }}
                    <span class="rounded-full px-2 py-0.5 text-xs {{ $active ? 'bg-white/20 text-white' : 'bg-vp-lavanda/25 text-vp-morado' }}">{{ $filter['count'] }}</span>
                </button>
            @endforeach
        </div>

        <p class="flex items-center gap-2 text-xs font-semibold text-vp-oscuro/60">
            <span class="relative flex h-2.5 w-2.5" aria-hidden="true">
                <span class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 motion-safe:animate-ping"></span>
                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
            </span>
            Se actualiza automáticamente
        </p>
    </div>

    {{-- Lista de pedidos --}}
    <div class="space-y-4">
        @forelse($orders as $order)
            @php $badge = $badges[$order->status] ?? $badges['pending']; @endphp

            <article wire:key="pedido-{{ $order->id }}" class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-bold text-vp-oscuro">Pedido #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-vp-oscuro/70">Enviado el {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-sm font-bold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>

                <p class="mt-3 text-vp-oscuro"><span class="text-vp-oscuro/60">Para:</span> <strong>{{ $order->resident->name }}</strong></p>

                <ul class="mt-3 divide-y divide-gray-100 rounded-xl bg-vp-beige/60 px-4">
                    @foreach($order->items as $item)
                        <li class="flex items-center justify-between gap-4 py-3">
                            <span class="font-semibold text-vp-oscuro">{{ $item->product->name }}</span>
                            <span class="shrink-0 font-extrabold text-vp-morado">× {{ $item->requested_quantity }}</span>
                        </li>
                    @endforeach
                </ul>

                {{-- Mensaje según el estado --}}
                @if($order->status === 'pending')
                    <p class="mt-4 flex items-center gap-2 text-sm font-semibold text-vp-oscuro/70">
                        <svg class="h-5 w-5 shrink-0 text-vp-morado" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        En espera de Almacén.
                    </p>
                @elseif($order->status === 'approved')
                    <p class="mt-4 flex items-center gap-2 text-sm font-semibold text-green-700">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
                        Aprobado y surtido el {{ $order->updated_at->format('d/m/Y H:i') }}.
                    </p>
                @else
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-red-700">Motivo del rechazo</p>
                        <p class="mt-1 font-semibold text-vp-oscuro">{{ $order->rejection_reason ?: 'Sin motivo registrado.' }}</p>
                        <p class="mt-1 text-sm text-vp-oscuro/70">Respondido el {{ $order->updated_at->format('d/m/Y H:i') }}.</p>
                    </div>
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-vp-gris bg-white p-10 text-center">
                @if($counts->sum() === 0)
                    <p class="font-bold text-vp-oscuro">Aún no has enviado pedidos.</p>
                    <a href="/" class="mt-4 inline-flex h-12 items-center rounded-xl bg-vp-morado px-6 font-bold text-white transition hover:bg-vp-oscuro">Hacer un pedido</a>
                @else
                    <p class="font-bold text-vp-oscuro">No tienes pedidos con ese estado.</p>
                    <button type="button" wire:click="setStatus('')"
                            class="mt-4 h-11 rounded-xl border-2 border-vp-morado px-5 text-sm font-bold text-vp-morado transition hover:bg-vp-morado hover:text-white">
                        Ver todos
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>