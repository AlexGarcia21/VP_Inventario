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
    public $products = [];

    // Variables para guardar lo que el usuario va seleccionando
    public $selectedFloor = null;
    public $selectedResident = null;
    public $cart = []; // Nuestro carrito de insumos de aseo personal

    // El método mount() es como un constructor, se ejecuta una sola vez al cargar la página
    public function mount()
    {
        $this->floors = Floor::all(); // Traemos los 5 pisos
        $this->products = Product::all(); // Traemos el catálogo de insumos
    }

    // Este en Livewire: se ejecuta automáticamente
    // cuando la variable $selectedFloor cambia desde un menú desplegable en el HTML
    public function updatedSelectedFloor($floor_id)
    {
        if ($floor_id) {
            // Filtramos los residentes por el piso seleccionado
            $this->residents = Resident::where('floor_id', $floor_id)->get();
        } else {
            $this->residents = [];
        }
        // Limpiamos el residente seleccionado por si cambian de piso a la mitad del proceso
        $this->selectedResident = null  ;
    }

    // Método para agregar insumos al carrito
    public function addToCart($product_id)
    {
        // Buscamos el producto en la base de datos para obtener su nombre
        $product = Product::find($product_id);

        if ($product) {
            // Cantidad que ya llevamos de este producto en el carrito
            $currentQuantity = $this->cart[$product_id]['quantity'] ?? 0;

            // Validamos que no se pida más de lo que hay físicamente en existencia.
            // Antes se podía armar una orden con cantidades imposibles y el error
            // solo aparecía hasta el momento de aprobarla en Almacén, rechazando
            // toda la orden por un solo insumo.
            if ($currentQuantity + 1 > $product->current_stock) {
                session()->flash('cart_error', "No hay suficiente stock de \"{$product->name}\". Disponible: {$product->current_stock}.");
                return;
            }

            // Verificamos si el producto ya existe en nuestro arreglo del carrito
            if (array_key_exists($product_id, $this->cart)) {
                // Si ya está, solo aumentamos la cantidad
                $this->cart[$product_id]['quantity']++;
            } else {
                // Si es la primera vez que lo agregan, lo metemos al arreglo
                $this->cart[$product_id] = [
                    'name' => $product->name,
                    'quantity' => 1
                ];
            }
        }
    }

    // Método para restar una unidad de un insumo en el carrito.
    // Si la cantidad llega a 0, se elimina el renglón por completo.
    public function decrementQuantity($product_id)
    {
        if (array_key_exists($product_id, $this->cart)) {
            $this->cart[$product_id]['quantity']--;

            if ($this->cart[$product_id]['quantity'] <= 0) {
                unset($this->cart[$product_id]);
            }
        }
    }

    // Método para quitar un insumo del carrito por completo
    public function removeFromCart($product_id)
    {
        // Verificamos si el producto está en el carrito
        if (array_key_exists($product_id, $this->cart)) {
            // Lo eliminamos del arreglo
            unset($this->cart[$product_id]);
        }
    }
    
    // Método para guardar el pedido en la base de datos
    public function submitOrder()
    {
        // 1. Doble validación de seguridad
        if (empty($this->cart) || empty($this->selectedResident)) {
            return;
        }

        // 2. Creamos la cabecera de la orden
        $order = Order::create([
            'resident_id' => $this->selectedResident,
            // NOTA: todavía no hay sistema de login implementado en el proyecto.
            // Usamos auth()->id() para que, en cuanto se implemente el login, esto
            // funcione automáticamente sin tocar este archivo; mientras tanto cae
            // en el usuario de prueba (ID 1) para no romper el flujo actual.
            'user_id' => auth()->id() ?? 1,
            'status' => 'pending' // Toda orden nace como pendiente
        ]);

        // 3. Guardamos el detalle: iteramos el carrito para registrar cada producto
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
        
        // 5. Mandamos un mensaje de éxito a la pantalla
        session()->flash('success', '¡Pedido registrado y enviado al almacén exitosamente!');
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}