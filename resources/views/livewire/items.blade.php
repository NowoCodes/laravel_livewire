<div class="p-6 bg-white border-b border-gray-200 sm:px-20">
  @if (session()->has('message'))
    <div class="relative px-6 py-4 mb-4 text-white bg-pink-500 border-0 rounded" role="alert" x-data="{show: true}" x-show="show">
      <span class="inline-block mr-5 text-xl align-middle">
        <svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <path
            d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
        </svg>
      </span>
      <span class="inline-block mr-8 align-middle">
        <b class="capitalize">{{ session('message') }}</b>
      </span>
      <button @click="show = false"
        class="absolute top-0 right-0 mt-4 mr-6 text-2xl font-semibold leading-none bg-transparent outline-none focus:outline-none">
        <span>×</span>
      </button>
    </div>
  @endif
  <div class="flex justify-between mt-8 text-2xl">
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

    <table class="w-full table-auto">
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
            <td class="px-4 py-2 border">{{ $item->id }}</td>
            <td class="px-4 py-2 border">{{ $item->name }}</td>
            <td class="px-4 py-2 border">{{ number_format($item->price, 2) }}</td>
            @if (!$active)
              <td class="px-4 py-2 border">{{ $item->status ? 'Active' : 'Not-Active' }}</td>
            @endif
            <td class="px-4 py-2 border">
              <x-jet-button wire:click="confirmItemEdit({{ $item->id }})" class="bg-yellow-500 hover:bg-yellow-700">
                {{ __('Edit') }}
              </x-jet-button>

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
  <x-jet-confirmation-modal wire:model="confirmingItemDeletion">
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
  </x-jet-confirmation-modal>

  <!-- Add Item Confirmation Modal -->
  <x-jet-dialog-modal wire:model="confirmingItemAddition">
    <x-slot name="title">
      <h1 class="uppercase">
        {{ isset($this->item->id) ? 'Edit Item' : 'Add Item' }}
      </h1>
    </x-slot>

    <x-slot name="content">
      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <x-jet-label for="name" value="{{ __('Name') }}" />
        <x-jet-input id="name" type="text" class="block w-full mt-1" wire:model.defer="item.name" />
        <x-jet-input-error for="item.name" class="mt-2" />
      </div>

      <!-- Price -->
      <div class="col-span-6 mt-2 sm:col-span-4">
        <x-jet-label for="price" value="{{ __('Price') }}" />
        <x-jet-input id="price" type="text" class="block w-full mt-1" wire:model.defer="item.price" />
        <x-jet-input-error for="item.price" class="mt-2" />
      </div>

      <!-- Status -->
      <div class="col-span-6 mt-2 sm:col-span-4">
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
