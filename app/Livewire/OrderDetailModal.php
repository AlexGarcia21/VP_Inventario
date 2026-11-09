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

    // Rechazo con motivo
    public $rejecting = false;
    public $rejectionReason = '';

    // Este atributo le dice a Livewire que escuche el evento 'openModal'
    #[On('openModal')]
    public function loadOrder($orderId)
    {
        $this->resetRejection();

        // Buscamos la orden con sus relaciones
        $this->order = Order::with(['resident', 'items.product'])->find($orderId);

        // Abrimos el modal
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->order = null; // Limpiamos los datos por seguridad
        $this->resetRejection();
    }

    // Muestra el cuadro para escribir el motivo del rechazo
    public function startReject()
    {
        $this->rejecting = true;
        $this->resetErrorBag();
    }

    // Regresa del cuadro de motivo a la vista normal del pedido
    public function cancelReject()
    {
        $this->resetRejection();
    }

    protected function resetRejection()
    {
        $this->rejecting = false;
        $this->rejectionReason = '';
        $this->resetErrorBag();
    }

    public function approveOrder()
    {
        try {
            DB::transaction(function () {
                // Releemos la orden con lockForUpdate() DENTRO de la transacción, en
                // vez de confiar en $this->order (que se cargó en memoria antes de
                // empezar). Sin esto, si la misma orden se aprueba dos veces casi al
                // mismo tiempo (doble clic, o dos almacenistas con el modal abierto),
                // ambas ejecuciones pasan el chequeo de "pending" y cada una descuenta
                // el stock por su cuenta, duplicando la salida de un mismo pedido.
                $order = Order::with('resident')->where('id', $this->order->id)->lockForUpdate()->first();

                // Se verifica que la orden no haya sido procesada previamente
                if ($order->status !== 'pending') {
                    throw new \Exception('Esta orden ya fue procesada.');
                }

                // Ordenamos por product_id para que todas las transacciones bloqueen
                // los productos siempre en el mismo orden y evitar deadlocks.
                $items = $order->items()->with('product')->orderBy('product_id')->get();

                foreach ($items as $item) {
                    // lockForUpdate() bloquea la fila del producto mientras dura la
                    // transacción: dos aprobaciones del mismo insumo no pueden dejar
                    // el stock en negativo.
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                    // Validamos que exista suficiente inventario
                    if ($product->current_stock < $item->requested_quantity) {
                        throw new \Exception("Stock insuficiente para: {$product->name}");
                    }

                    // Restamos y guardamos el nuevo stock del producto
                    $product->current_stock -= $item->requested_quantity;
                    $product->save();

                    // Registramos la salida en la bitácora de inventario
                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'user_id'    => auth()->id() ?? 1,
                        'type'       => 'exit',
                        'quantity'   => $item->requested_quantity,
                        'notes'      => "Salida por orden #{$order->id} - Residente: {$order->resident->name}",
                    ]);
                }

                // Marcamos la orden completa como 'aprobada'
                $order->status = 'approved';
                $order->save();

                $this->order = $order;
            });

            // Si todo salió bien, cerramos el modal y avisamos a los demás componentes
            $this->closeModal();
            $this->dispatch('orderApproved');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function rejectOrder()
    {
        // Validamos ANTES del try: si no, la excepción de validación caería en el
        // catch y se mostraría como un error genérico en vez de bajo el campo.
        $this->validate([
            'rejectionReason' => 'required|string|min:5|max:255',
        ], [
            'rejectionReason.required' => 'Escribe el motivo del rechazo para que enfermería sepa qué pasó.',
            'rejectionReason.min'      => 'El motivo debe tener al menos 5 caracteres.',
            'rejectionReason.max'      => 'El motivo no puede pasar de 255 caracteres.',
        ]);

        try {
            DB::transaction(function () {
                // Mismo patrón que approveOrder(): releemos y bloqueamos la orden
                // dentro de la transacción para evitar doble procesamiento.
                $order = Order::where('id', $this->order->id)->lockForUpdate()->first();

                if ($order->status !== 'pending') {
                    throw new \Exception('Esta orden ya fue procesada.');
                }

                // Rechazamos sin alterar el stock y guardamos el motivo
                $order->status = 'rejected';
                $order->rejection_reason = trim($this->rejectionReason);
                $order->save();

                $this->order = $order;
            });

            $this->closeModal();
            $this->dispatch('orderRejected');

        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order-detail-modal');
    }
}