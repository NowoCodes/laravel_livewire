<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
  <div class="mt-8 text-2xl">
    Items
  </div>

  {{ $query }}

  <div class="mt-6">
    <div class="flex justify-between">
      <div>
        <input wire:model.debounce.500ms="q" type="search" placeholder="Search">
      </div>
      <div class="mr-2">
        <input type="checkbox" class="mr-2 leading-tight" wire:model="active"> Active Only
      </div>
    </div>

    <table class="table-auto w-full">
      <thead>
        <tr>
          <th class="px-4 py-2">
            <div class="flex items-center">
              <button wire:click="sortBy('id')">ID</button>
              <x-sort-icon sortField="id" :sortBy="$sortBy" :sortAsc="$sortAsc" />
            </div>
          </th>
          <th class="px-4 py-2">
            <div class="flex items-center">
              <button wire:click="sortBy('name')">Name</button>
              <x-sort-icon sortField="name" :sortBy="$sortBy" :sortAsc="$sortAsc" />
            </div>
          </th>
          <th class="px-4 py-2">
            <div class="flex items-center">
              <button wire:click="sortBy('price')">Price</button>
              <x-sort-icon sortField="price" :sortBy="$sortBy" :sortAsc="$sortAsc" />
            </div>
          </th>
          @if (!$active)
            <th class="px-4 py-2">
              <div class="flex items-center">Status</div>
            </th>
          @endif
          <th class="px-4 py-2">
            <div class="flex items-center">Actions</div>
          </th>
        </tr>
      </thead>

      <tbody>
        @foreach ($items as $item)
          <tr>
            <td class="border px-4 py-2">{{ $item->id }}</td>
            <td class="border px-4 py-2">{{ $item->name }}</td>
            <td class="border px-4 py-2">{{ number_format($item->price, 2) }}</td>
            @if (!$active)
              <td class="border px-4 py-2">{{ $item->status ? 'Active' : 'Not-Active' }}</td>
            @endif
            <td class="border px-4 py-2">
              {{ __('Edit') }}

              <x-jet-danger-button wire:click="confirmItemDeletion({{ $item->id }})" wire:loading.attr="disabled">
                {{ __('Delete') }}
              </x-jet-danger-button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $items->links() }}
  </div>

  <!-- Delete User Confirmation Modal -->
  <x-jet-dialog-modal wire:model="confirmingItemDeletion">
    <x-slot name="title">
      {{ __('Delete Item') }}
    </x-slot>

    <x-slot name="content">
      {{ __('Are you sure you want to delete this item?') }}
    </x-slot>

    <x-slot name="footer">
      {{-- <x-jet-secondary-button wire:click="$toggle('confirmingItemDeletion')" wire:loading.attr="disabled"> --}}
      <x-jet-secondary-button wire:click="$set('confirmingItemDeletion', false)" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-jet-secondary-button>

      <x-jet-danger-button class="ml-2" wire:click="deleteItem({{ $confirmingItemDeletion }})"
        wire:loading.attr="disabled">
        {{ __('Delete Item') }}
      </x-jet-danger-button>
    </x-slot>
  </x-jet-dialog-modal>
</div>
