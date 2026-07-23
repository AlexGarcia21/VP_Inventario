<div class="min-h-screen bg-vp-beige p-8 font-sans text-vp-oscuro">
    <!-- Encabezado de la página -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-vp-lavanda">Gestión de Insumos</h1>
        <p class="text-vp-lavanda font-medium tracking-wide">VILLA PLATA - Vida Asistida</p>
    </div>  

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Panel Izquierdo: Formularios de Asignación -->
        <div class="md:col-span-4 bg-white p-6 rounded-xl shadow-sm border-t-4 border-vp-lavanda">
            <h2 class="text-xl font-bold mb-6 text-vp-oscuro flex items-center gap-2">
                Asignar Insumos
            </h2>

            <!-- Selector de Piso -->
          <div class="mb-5">
        <label class="block text-sm font-semibold text-vp-gris mb-2">Seleccionar Piso</label>
        <!-- Agregamos wire:model.live para que escuche el cambio al instante -->
        <select wire:model.live="selectedFloor" class="w-full bg-vp-beige border border-gray-200 text-vp-oscuro rounded-lg p-3 focus:ring-vp-morado focus:border-vp-morado transition-colors">
            <option value="">Elige un piso...</option>
            <!-- Iteramos sobre tus pisos reales de la BD -->
            @foreach($floors as $floor)
                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
            @endforeach
        </select>
    </div>  

            <!-- Selector de Residente -->
           <div class="mb-5">
        <label class="block text-sm font-semibold text-vp-gris mb-2">Seleccionar Residente</label>
        <!-- Habilitamos o deshabilitamos dependiendo de si ya hay un piso seleccionado -->
        <select wire:model.live="selectedResident" 
                class="w-full border border-gray-200 rounded-lg p-3 transition-colors focus:ring-vp-morado focus:border-vp-morado
                {{ empty($selectedFloor) ? 'bg-gray-100 text-gray-400 opacity-50 cursor-not-allowed' : 'bg-vp-beige text-vp-oscuro' }}" 
                {{ empty($selectedFloor) ? 'disabled' : '' }}>
            
            <option value="">Primero selecciona un piso</option>
            <!-- Iteramos sobre los residentes (si existen) -->
            @if(!empty($residents))
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

        <!-- Panel Derecho: Catálogo y Carrito -->
        <div class="md:col-span-8">
            
            <!-- Catálogo de Insumos -->
<div class="bg-white p-6 rounded-xl shadow-sm mb-6 border-t-4 border-vp-lavanda">
    <h2 class="text-xl font-bold mb-4 text-vp-oscuro">Catálogo de Insumos</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Iteramos sobre los productos de la BD -->
        @foreach($products as $product)
        <div class="bg-vp-beige p-4 rounded-xl border border-gray-100 flex flex-col items-center hover:shadow-md transition-shadow">
            <!-- Un icono genérico por ahora -->
            <span class="text-3xl mb-3">📦</span>
            <span class="font-bold text-vp-oscuro text-sm mb-1 text-center">{{ $product->name }}</span>
            <span class="text-xs text-vp-gris mb-4">Stock: {{ $product->current_stock }}</span>
            
            <!-- Botón que mandará llamar a la función de agregar al carrito -->
            <button wire:click="addToCart({{ $product->id }})" class="bg-vp-morado hover:bg-vp-oscuro text-white text-xs font-bold py-2 px-4 rounded-lg w-full transition-colors">
                Agregar
            </button>
        </div>
        @endforeach
    </div>
</div>
           <!-- Carrito de Asignación -->
<div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-vp-lavanda">
    <h2 class="text-xl font-bold mb-4 text-vp-oscuro">Resumen de Asignación</h2>
    
    <!-- Evaluamos si el carrito está vacío -->
    @if(empty($cart))
        <div class="bg-vp-beige rounded-lg p-4 mb-6 text-center border border-dashed border-vp-gris">
            <p class="text-vp-gris text-sm">Aún no has agregado insumos.</p>
        </div>
    @else
        <!-- Si hay productos, los mostramos en una lista -->
        <ul class="mb-6 space-y-3">
            @foreach($cart as $id => $item)
                <li class="flex justify-between items-center bg-vp-beige p-3 rounded-lg border border-gray-200 shadow-sm">
                    <div>
                        <p class="font-bold text-vp-oscuro text-sm">{{ $item['name'] }}</p>
                        <p class="text-xs text-vp-morado font-semibold">Cantidad: {{ $item['quantity'] }}</p>
                    </div>
                    <!-- Botón para quitar del carrito -->
                    <button wire:click="removeFromCart({{ $id }})" class="text-red-500 hover:text-red-700 text-xs font-bold transition-colors">
                        Quitar ✖
                    </button>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Botón de Enviar Pedido (Se deshabilita si no hay carrito o no hay residente seleccionado) -->
    <button 
        wire:click="submitOrder"
        class="w-full bg-vp-oscuro hover:bg-black text-white font-bold py-3 rounded-lg transition-colors shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
        @if(empty($cart) || empty($selectedResident)) disabled @endif>
        Enviar pedido a almacén
    </button>

    <!-- Alerta de éxito -->
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-xl shadow-sm">
            <p class="font-bold flex items-center">
                <span class="mr-2">✅</span> {{ session('success') }}
            </p>
        </div>
    @endif
</div>
        </div>
    </div>
</div>