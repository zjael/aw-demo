<?php

use Livewire\Volt\Component;
use App\Enums\CallEventStatusEnum;

new class extends Component {
    public $status = '';
    public $tableId = -1;
}; ?>

<div class="flex flex-col p-2 mx-auto bg-gray-800 rounded-md shadow-lg min-w-24">
    <h1 class="text-xs font-semibold tracking-widest text-center text-white uppercase">{{ $status }}</h1>
    <p class="text-xs font-semibold tracking-widest text-center text-white uppercase">Bord: {{ $tableId }}</p>
    <div class="self-center mt-1">
        <x-primary-button class="px-6 py-2 text-white bg-gray-700 rounded-full"
            x-on:click="
                Echo.connector.pusher.send_event('CallForWaiterStatus',
                    JSON.stringify({
                        status: `{{ CallEventStatusEnum::ACCEPTED->value }}`,
                        tableId: `{{ $tableId }}`,
                    }), `tables.{{ $tableId }}.call`);
            ">
            Accept
        </x-primary-button>
    </div>
</div>
