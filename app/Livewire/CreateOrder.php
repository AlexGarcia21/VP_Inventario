<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Floor;
use App\Models\Resident;
use App\Models\Product;

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

    // Este método es mágico en Livewire: se ejecuta automáticamente
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

    public function render()
    {
        return view('livewire.create-order');
    }
}