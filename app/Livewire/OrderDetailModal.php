<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Livewire\Attributes\On; // escuchar eventos en Livewire 
use Illuminate\Support\Facades\DB; 

class OrderDetailModal extends Component
{
    public $isOpen = false; // Controla si el modal se ve o no
    public $order = null;   // Aquí se guardan los datos del pedido

    // Este atributo le dice a Livewire que escuche el evento 'openModal'
    #[On('openModal')]
    public function loadOrder($orderId)
    {
        // Buscamos la orden con sus relaciones 
        $this->order = Order::with(['resident', 'items.product'])->find($orderId);
        
        // Abrimos el modal
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->order = null; // Limpiamos los datos por seguridad
    }

    public function approveOrder()
    {   
        try {
            DB::transaction(function () {
                $order = Order::with('resident')->where('id', $this->order->id)->lockForUpdate()->first();

                // Se Verifica  que la orden no haya sido aprobada previamente
                if ($order->status !== 'pending') {
                    throw new \Exception('Esta orden ya fue procesada.');
                }

                /* Recorremos los insumos para restar el stock físicamente.
                Usamos lockForUpdate() para bloquear la fila del producto mientras
                dura la transacción: así, si dos aprobaciones del mismo insumo
                ocurren al mismo tiempo, la segunda espera a que la primera termine
                en vez de leer un stock desactualizado y dejarlo en negativo.
                
                Ordenamos por product_id antes de recorrerlos para que, si una orden
                tiene varios insumos, todas las transacciones concurrentes bloqueen
                los productos siempre en el mismo orden numérico. Esto evita un
                deadlock de base de datos en el caso (poco probable, pero posible)
                de que dos órdenes compartan insumos en secuencia inversa. */

                $items = $order->items()->with('product')->orderBy('product_id')->get();

                foreach ($items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                    // Validamos que exista suficiente inventario
                    if ($product->current_stock < $item->requested_quantity) {
                        throw new \Exception("Stock insuficiente para: {$product->name}");
                    }

                    // Restamos y guardamos el nuevo stock del producto
                    $product->current_stock -= $item->requested_quantity;
                    $product->save();

                    /* Registramos el movimiento en la bitácora de inventario.
                    InventoryEntry ya registraba las entradas ('entry') al recibir
                    insumos de proveedor, pero aquí nunca se registraba la salida
                    ('exit') al surtir una orden: la tabla inventory_transactions
                    quedaba incompleta como historial de auditoría, aunque el
                    diseño original (ver comentario en su migración) contemplaba
                    registrar ahí tanto entradas como salidas. */
                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'user_id'    => auth()->id() ?? 1,
                        'type'       => 'exit',
                        'quantity'   => $item->requested_quantity,
                        'notes'      => "Salida por orden #{$order->id} - Residente: {$order->resident->name}",
                    ]);
                }

                //Marcamos la orden completa como 'aprobada'
                $order->status = 'approved';
                $order->save();
                $this->order = $order;
            });

            // Si todo salió bien, cerramos el modal
            $this->closeModal();
            
            // Le avisamos a la tabla principal que recargue sus datos
            $this->dispatch('orderApproved');

        } catch (\Exception $e) {
            // Manejo de errores (Se puede conectar luego con una alerta visual)
            session()->flash('error', $e->getMessage());
           
        }
    }

    public function render()
    {
        return view('livewire.order-detail-modal');
    }

    public function rejectOrder()
    {
        try {
            DB::transaction(function () {
                // Igual que en approveOrder(): releemos la orden con lockForUpdate()
                $order = Order::where('id', $this->order->id)->lockForUpdate()->first();

                // Verificamos que la orden no haya sido procesada antes
                if ($order->status !== 'pending') {
                    throw new \Exception('Esta orden ya fue procesada.');
                }

                // Cambiamos el estado a rechazado sin alterar el stock
                $order->status = 'rejected';
                $order->save();

                $this->order = $order;
            });

            // Cerramos modal y disparamos evento
            $this->closeModal();
            $this->dispatch('orderRejected');

        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
//este modal es para mostrar los detalles de la orden y permitir al usuario marcarla como surtida. Se abre cuando el usuario hace click en "Revisar y Surtir" en la tabla de órdenes pendientes.
//en si, una ventana emergente muestra la info o pedir datos sin tener que recargar la página. Esto mejora la experiencia del usuario y hace que la aplicación sea más interactiva.