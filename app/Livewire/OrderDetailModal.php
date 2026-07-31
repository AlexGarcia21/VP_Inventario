<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\On; // escuchar eventos en Livewire 

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

    public function render()
    {
        return view('livewire.order-detail-modal');
    }
}
//este modal es para mostrar los detalles de la orden y permitir al usuario marcarla como surtida. Se abre cuando el usuario hace click en "Revisar y Surtir" en la tabla de órdenes pendientes.
//en si, una ventana emergente muestra la info o pedir datos sin tener que recargar la página. Esto mejora la experiencia del usuario y hace que la aplicación sea más interactiva.