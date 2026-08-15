<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
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
                // Se Verifica  que la orden no haya sido aprobada previamente
                if ($this->order->status !== 'pending') {
                    throw new \Exception('Esta orden ya fue procesada.');
                }

                // Recorremos los insumos para restar el stock físicamente
                foreach ($this->order->items as $item) {
                    $product = $item->product;

                    // Validamos que exista suficiente inventario
                    if ($product->current_stock < $item->requested_quantity) {
                        throw new \Exception("Stock insuficiente para: {$product->name}");
                    }

                    // Restamos y guardamos el nuevo stock del producto
                    $product->current_stock -= $item->requested_quantity;
                    $product->save();
                }

                //Marcamos la orden completa como 'aprobada'
                $this->order->status = 'approved';
                $this->order->save();
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
}
//este modal es para mostrar los detalles de la orden y permitir al usuario marcarla como surtida. Se abre cuando el usuario hace click en "Revisar y Surtir" en la tabla de órdenes pendientes.
//en si, una ventana emergente muestra la info o pedir datos sin tener que recargar la página. Esto mejora la experiencia del usuario y hace que la aplicación sea más interactiva.
