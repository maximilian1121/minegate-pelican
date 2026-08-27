<x-filament-panels::page>
    @if (($this->minegateHealth()['status'] ?? 'down') != 'ok')
        <div class="fi-ta-text fi-ta-text-item fi-size-sm">Minegate is down!</div>
    @else
        @if (!empty($this->subdomain))
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-1">
                    <span class="text-lg font-medium">
                        Current domain
                    </span>

                    <div class="flex items-center gap-2 text-md">
                        <span>{{ $this->subdomain }}.{{ $this->minegateHealth()['root_domain'] ?? 'ERR_DOMAIN' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-filament-forms::field-wrapper id="edit-host" label="Server Address" :required="true">
                        <div class="fi-input-wrp">
                            <input wire:model="host" id="host" type="text" class="fi-input block w-full"
                                placeholder="Enter new address..." />
                        </div>
                    </x-filament-forms::field-wrapper>

                    <x-filament-forms::field-wrapper id="edit-port" label="Server Port" :required="true">
                        <div class="fi-input-wrp">
                            <input wire:model="port" id="port" type="number" min="1" max="65535" class="fi-input block w-full"
                                placeholder="Enter new port..." />
                        </div>
                    </x-filament-forms::field-wrapper>
                </div>

                <div class="flex items-center gap-3">
                    <x-filament::button type="button" wire:click="updateSubdomain">
                        Save Changes
                    </x-filament::button>

                    <x-filament::button color="danger" icon="heroicon-m-trash" type="button" x-data
                        @click="$dispatch('open-modal', { id: 'delete-confirm-modal' })">
                        Remove
                    </x-filament::button>
                </div>
            </div>
        @else
            <div class="fi-ta-text fi-ta-text-item fi-size-sm">No subdomain exists for this server!</div>

            <x-filament::button type="button" x-data @click="$dispatch('open-modal', { id: 'create-new-modal' })">
                Add Subdomain
            </x-filament::button>
        @endif
    @endif

    <x-filament::modal id="create-new-modal">
        <x-slot name="heading">
            Add a subdomain
        </x-slot>

        <div class="space-y-4">
            <x-filament-forms::field-wrapper id="new-subdomain" label="Subdomain Name" :required="true">
                <div class="fi-input-wrp">
                    <input wire:model="newSubdomain" id="new-subdomain" type="text" class="fi-input block w-full"
                        placeholder="Enter subdomain..." />
                </div>
            </x-filament-forms::field-wrapper>
            <x-filament-forms::field-wrapper id="new-host" label="Server address" :required="true"
                title="Must be accessible from Minegate">
                <div class="fi-input-wrp">
                    <input wire:model="newHost" id="new-host" type="text" class="fi-input block w-full"
                        placeholder="Enter address..." />
                </div>
            </x-filament-forms::field-wrapper>
            <x-filament-forms::field-wrapper id="new-port" label="Server port" :required="true"
                title="Must be accessible from Minegate">
                <div class="fi-input-wrp">
                    <input wire:model="newPort" id="new-port" type="number" class="fi-input block w-full"
                        placeholder="Enter port..." max="65535" min="1" />
                </div>
            </x-filament-forms::field-wrapper>
        </div>

        <x-slot name="footer">
            <div class="flex w-full gap-3">
                <x-filament::button type="button" color="gray" class="flex-1"
                    @click="$dispatch('close-modal', { id: 'create-new-modal' })">
                    Cancel
                </x-filament::button>

                <x-filament::button type="button" wire:click="createSubdomain" class="flex-1"
                    @click="$dispatch('close-modal', { id: 'create-new-modal' })">
                    Save
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>

    <x-filament::modal id="delete-confirm-modal" alignment="center">
        <x-slot name="heading">
            Are you sure?
        </x-slot>

        <p class="text-sm text-gray-500">
            This will permanently remove your subdomain mapping. You can always add it back later.
        </p>

        <x-slot name="footer">
            <div class="flex w-full gap-3">
                <x-filament::button type="button" color="gray" class="flex-1"
                    @click="$dispatch('close-modal', { id: 'delete-confirm-modal' })">
                    Cancel
                </x-filament::button>

                <x-filament::button type="button" color="danger" wire:click="deleteSubdomain" class="flex-1"
                    @click="$dispatch('close-modal', { id: 'delete-confirm-modal' })">
                    Yes, Remove
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>