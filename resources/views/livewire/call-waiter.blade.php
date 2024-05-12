<?php

use App\Events\CallForWaiter;
use App\Enums\CallEventStatusEnum;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Log;

new class extends Component {
    public $status = '';
    public $waiting = false;
    public $cooldown = false;
    public $tableId = 1; // TODO: For testing, should get ID from slug "scanned QR page"

    protected function getListeners()
    {
        return [
            "echo:tables.{$this->tableId}.call,CallForWaiterStatus" => 'notify',
            'called-waiter' => 'callCompleted',
            'error-occured' => 'callFailed',
        ];
    }

    public function notify($e)
    {
        $tableId = $e['tableId'] ?? null;
        $status = $e['status'] ?? null;

        if ($status === CallEventStatusEnum::ACCEPTED->value) {
            Log::debug('Waiter on way');
            $this->waiting = false;
            $this->dispatch('waiter-on-way');
        }
    }

    public function callCompleted()
    {
        Log::debug('Waiting for waiter response');
        $this->waiting = true;
        $this->dispatch('waiting-for-response');
    }

    public function callFailed()
    {
        Log::debug('Call failed');
        $this->waiting = false;
        $this->dispatch('call-failed');
    }
}; ?>

<div class="flex flex-col items-center justify-center">
    <x-action-message class="font-semibold tracking-widest text-center uppercase text-md" on="waiter-on-way">
        {{ __('Waiter is on his way.') }}
    </x-action-message>

    <x-primary-button class="text-nowrap"
        x-on:click="
            const success = Echo.connector.pusher.send_event('CallForWaiter',
                JSON.stringify({
                    tableId: `{{ $tableId }}`,
                }), `tables.calls`);
            if (success) {
                $dispatch('called-waiter');
            } else {
                $dispatch('error-occured');
            }
    ">
        <span x-show="!$wire.waiting" x-transition>
            {{ __('Call waiter') }}
        </span>
        <div x-show="$wire.waiting">
            <x-ei-spinner-3 class="w-8 h-8 black animate-spin" />
        </div>
    </x-primary-button>
</div>
