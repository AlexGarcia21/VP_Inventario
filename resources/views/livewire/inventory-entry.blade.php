@php
    $selected = $product_id ? $products->firstWhere('id', $product_id) : null;
    $qty      = (is_numeric($quantity) && (int) $quantity > 0) ? (int) $quantity : null;
@endphp

<div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-8">

    {{-- Confirmación --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" role="status"
             class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error --}}
    @if (session()->has('error'))
        <div role="alert" class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16.5v.01"/></svg>
            <p class="text-sm font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="registerEntry" class="space-y-6" novalidate>

        {{-- Insumo --}}
        <div>
            <label for="insumo" class="mb-2 block text-sm font-bold text-vp-oscuro">Insumo recibido <span class="text-vp-morado">*</span></label>
            <select id="insumo" wire:model.live="product_id"
                    @error('product_id') aria-invalid="true" @enderror
                    class="block h-14 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('product_id') border-red-400 @else border-gray-200 @enderror">
                <option value="">Selecciona un insumo del catálogo…</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}{{ $product->brand ? ' · '.$product->brand : '' }}</option>
                @endforeach
            </select>
            @error('product_id')
                <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Cantidad --}}
        <div>
            <label for="cantidad" class="mb-2 block text-sm font-bold text-vp-oscuro">Cantidad recibida (unidades) <span class="text-vp-morado">*</span></label>
            <input id="cantidad" type="number" inputmode="numeric" min="1" wire:model.live.debounce.300ms="quantity"
                   placeholder="Ej. 50"
                   @error('quantity') aria-invalid="true" @enderror
                   class="block h-14 w-full rounded-xl border bg-vp-beige px-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15 @error('quantity') border-red-400 @else border-gray-200 @enderror">
            @error('quantity')
                <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Vista previa de existencias --}}
        @if($selected)
            <div class="rounded-xl border border-vp-lavanda/50 bg-vp-lavanda/10 p-4" aria-live="polite">
                <p class="text-xs font-bold uppercase tracking-wide text-vp-oscuro/60">Existencias de {{ $selected->name }}</p>
                <div class="mt-2 flex flex-wrap items-end gap-x-4 gap-y-2 text-vp-oscuro">
                    <div>
                        <p class="text-sm text-vp-oscuro/70">Actual</p>
                        <p class="text-2xl font-extrabold leading-none">{{ $selected->current_stock }}</p>
                    </div>
                    @if($qty)
                        <span class="pb-0.5 text-xl font-bold text-vp-oscuro/50" aria-hidden="true">+</span>
                        <div>
                            <p class="text-sm text-vp-oscuro/70">Entrada</p>
                            <p class="text-2xl font-extrabold leading-none">{{ $qty }}</p>
                        </div>
                        <span class="pb-0.5 text-xl font-bold text-vp-oscuro/50" aria-hidden="true">=</span>
                        <div>
                            <p class="text-sm text-vp-oscuro/70">Quedará en</p>
                            <p class="text-2xl font-extrabold leading-none text-vp-morado">{{ $selected->current_stock + $qty }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Notas --}}
        <div>
            <label for="notas" class="mb-2 block text-sm font-bold text-vp-oscuro">Notas o referencia de factura <span class="font-normal text-vp-oscuro/60">(opcional)</span></label>
            <input id="notas" type="text" wire:model="notes" maxlength="255"
                   placeholder="Ej. Factura #9812 - Proveedor Farmacias"
                   class="block h-14 w-full rounded-xl border border-gray-200 bg-vp-beige px-4 text-base text-vp-oscuro placeholder:text-vp-gris transition focus:border-vp-morado focus:outline-none focus:ring-4 focus:ring-vp-morado/15">
            @error('notes')
                <p role="alert" class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Registrar --}}
        <button type="submit" wire:loading.attr="disabled" wire:target="registerEntry"
                class="flex h-14 w-full items-center justify-center gap-2 rounded-xl bg-vp-morado text-base font-bold text-white shadow-sm transition hover:bg-vp-oscuro focus:outline-none focus:ring-4 focus:ring-vp-morado/30 disabled:cursor-wait disabled:opacity-70">
            <svg wire:loading wire:target="registerEntry" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span wire:loading.remove wire:target="registerEntry">Registrar entrada</span>
            <span wire:loading wire:target="registerEntry">Registrando…</span>
        </button>
    </form>
</div>