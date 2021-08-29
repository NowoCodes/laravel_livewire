<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public $active, $q;

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
    ];

    public function render()
    {
        $items = Item::where('user_id', auth()->id())
            ->when($this->q, function ($query) {
                return $query->where(function ($query) {
                    $query->where('name', 'LIKE', '%' . $this->q . '%')
                        ->orWhere('price', 'LIKE', '%' . $this->q . '%');
                });
            })
            ->when($this->active, function ($query) {
                return $query->active();
            });

        $query = $items->toSql();
        $items = $items->paginate(10);

        return view('livewire.items', compact('items', 'query'));
    }

    public function updatingActive()
    {
        $this->resetPage();
    }

    public function updatingQ()
    {
        $this->resetPage();
    }
}
