<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public function render()
    {
        $items = Item::where('user_id', auth()->id())
            ->paginate(10);

        return view('livewire.items', compact('items'));
    }
}
