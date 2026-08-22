<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Resident;
use App\Models\Floor;
use Livewire\WithPagination;

class ResidentManager extends Component
{
    use WithPagination;

    // Campos del formulario
    public $resident_id = null;
    public $name = '';
    public $floor_id = '';

    // Filtros y control de estado
    public $isEditing = false;
    public $search = '';
    public $selectedFloorFilter = '';

    protected function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'floor_id' => 'required|exists:floors,id',
        ];
    }

    protected $messages = [
        'name.required'     => 'El nombre del residente es obligatorio.',
        'floor_id.required' => 'Debes asignar un piso al residente.',
        'floor_id.exists'   => 'El piso seleccionado no es válido.',
    ];

    public function resetInputFields()
    {
        $this->resident_id = null;
        $this->name = '';
        $this->floor_id = '';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        Resident::updateOrCreate(
            ['id' => $this->resident_id],
            [
                'name'     => $this->name,
                'floor_id' => $this->floor_id,
            ]
        );

        session()->flash('message', $this->isEditing ? '¡Residente actualizado con éxito!' : '¡Residente registrado con éxito!');
        $this->resetInputFields();
        $this->dispatch('residentSaved');
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);
        $this->resident_id = $resident->id;
        $this->name = $resident->name;
        $this->floor_id = $resident->floor_id;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        Resident::findOrFail($id)->delete();
        session()->flash('message', 'Residente eliminado del sistema.');
    }

    public function render()
    {
        $floors = Floor::orderBy('id', 'asc')->get();

        $residents = Resident::with('floor')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedFloorFilter, function ($query) {
                $query->where('floor_id', $this->selectedFloorFilter);
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.resident-manager', compact('residents', 'floors'));
    }
}