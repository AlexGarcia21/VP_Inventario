<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Floor;
use App\Models\Resident;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CreateOrder extends Component
{
    // Variables públicas que la vista HTML podrá leer y modificar en tiempo real
    public $floors = [];
    public $residents = [];

    // Variables para guardar lo que el usuario va seleccionando
    public $selectedFloor = null;
    public $selectedResident = null;
    public $cart = []; // Nuestro carrito de insumos de aseo personal

    // Filtros del catálogo
    public $search = '';
    public $onlyAvailable = false;

    // El método mount() se ejecuta una sola vez al cargar la página
    public function mount()
    {
        $this->floors = Floor::all();
        // El catálogo ya no se guarda como propiedad pública: se consulta en
        // render() según los filtros, para no enviar todos los productos al
        // navegador en cada interacción.
    }

    // Se ejecuta automáticamente cuando cambia $selectedFloor desde el menú
    public function updatedSelectedFloor($floor_id)
    {
        if ($floor_id) {
            $this->residents = Resident::where('floor_id', $floor_id)->get();
        } else {
            $this->residents = [];
        }
        // Limpiamos el residente seleccionado por si cambian de piso a la mitad del proceso
        $this->selectedResident = null;
    }

    // Quita la búsqueda y el filtro de disponibilidad
    public function clearFilters()
    {
        $this->search = '';
        $this->onlyAvailable = false;
    }

    // Método para agregar insumos al carrito
    public function addToCart($product_id)
    {
        $product = Product::find($product_id);

        if ($product) {
            // Cantidad que ya llevamos de este producto en el carrito
            $currentQuantity = $this->cart[$product_id]['quantity'] ?? 0;

            // Validamos que no se pida más de lo que hay físicamente en existencia
            if ($currentQuantity + 1 > $product->current_stock) {
                session()->flash('cart_error', "No hay suficiente stock de \"{$product->name}\". Disponible: {$product->current_stock}.");
                return;
            }

            if (array_key_exists($product_id, $this->cart)) {
                $this->cart[$product_id]['quantity']++;
            } else {
                $this->cart[$product_id] = [
                    'name' => $product->name,
                    'quantity' => 1
                ];
            }
        }
    }

    // Resta una unidad; si llega a 0, elimina el renglón por completo
    public function decrementQuantity($product_id)
    {
        if (array_key_exists($product_id, $this->cart)) {
            $this->cart[$product_id]['quantity']--;

            if ($this->cart[$product_id]['quantity'] <= 0) {
                unset($this->cart[$product_id]);
            }
        }
    }

    // Quita un insumo del carrito por completo
    public function removeFromCart($product_id)
    {
        if (array_key_exists($product_id, $this->cart)) {
            unset($this->cart[$product_id]);
        }
    }

    // Guarda el pedido en la base de datos
    public function submitOrder()
    {
        // 1. Doble validación de seguridad
        if (empty($this->cart) || empty($this->selectedResident)) {
            return;
        }

        // 2. Creamos la cabecera de la orden
        $order = Order::create([
            'resident_id' => $this->selectedResident,
            'user_id' => auth()->id() ?? 1,
            'status' => 'pending' // Toda orden nace como pendiente
        ]);

        // 3. Guardamos el detalle: un registro por cada producto del carrito
        foreach ($this->cart as $product_id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product_id,
                'requested_quantity' => $item['quantity']
            ]);
        }

        // 4. Limpiamos la pantalla para el siguiente pedido
        $this->cart = [];
        $this->selectedResident = null;

        // 5. Mensaje de éxito
        session()->flash('success', '¡Pedido registrado y enviado al almacén exitosamente!');
    }

    public function render()
    {
        $search = trim((string) $this->search);

        $products = Product::query()
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")))
            ->when($this->onlyAvailable, fn ($q) => $q->where('current_stock', '>', 0))
            ->orderBy('name')
            ->get();

        return view('livewire.create-order', [
            'products'      => $products,
            'totalProducts' => Product::count(),
        ]);
    }
}