<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
  <div class="mt-8 text-2xl flex justify-between">
    <div>Items</div>
    <div class="mr-2">
      <x-jet-button wire:click="confirmItemAddition" class="bg-blue-500 hover:bg-blue-700">
        {{ __('Add New Item') }}
      </x-jet-button>
    </div>
  </div>

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

  <!-- Add Item Confirmation Modal -->
  <x-jet-dialog-modal wire:model="confirmingItemAddition">
    <x-slot name="title">
        <h1 class="uppercase">
            {{ __('Add Item') }}
        </h1>
    </x-slot>

    <x-slot name="content">
      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <x-jet-label for="name" value="{{ __('Name') }}" />
        <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="item.name" />
        <x-jet-input-error for="item.name" class="mt-2" />
      </div>

      <!-- Price -->
      <div class="col-span-6 sm:col-span-4 mt-2">
        <x-jet-label for="price" value="{{ __('Price') }}" />
        <x-jet-input id="price" type="text" class="mt-1 block w-full" wire:model.defer="item.price" />
        <x-jet-input-error for="item.price" class="mt-2" />
      </div>

      <!-- Status -->
      <div class="col-span-6 sm:col-span-4 mt-2">
        <label for="status" class="flex items-center">
          <x-jet-input id="status" type="checkbox" class="form-checkbox" wire:model.defer="item.status" />
          <span class="ml-2 text-sm text-gray-600">Active</span>
        </label>
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-jet-secondary-button wire:click="$toggle('confirmingItemAddition')" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-jet-secondary-button>

      <x-jet-danger-button class="ml-2 bg-green-500 hover:bg-green-700" wire:click="saveItem()"
        wire:loading.attr="disabled">
        {{ __('Save Item') }}
      </x-jet-danger-button>
    </x-slot>
  </x-jet-dialog-modal>
</div>
