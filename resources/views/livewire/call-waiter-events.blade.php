<?php

use App\Events\CallForWaiter;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Log;
use App\Enums\CallEventStatusEnum;

new class extends Component {
    protected $listeners = ['echo:tables.calls,CallForWaiterStatus' => 'handleNewCall'];

    public $calls = [];

    public function handleNewCall($e)
    {
        Log::debug('New call received: ', $e);
        $status = $e['status'] ?? null;
        $tableId = $e['tableId'] ?? null;

        if ($status === CallEventStatusEnum::ACCEPTED->value || $status === CallEventStatusEnum::REJECTED->value) {
            $this->calls = array_filter($this->calls, function ($call) use ($tableId) {
                return $call !== $tableId;
            });
        }

        if ($status === CallEventStatusEnum::PENDING->value) {
            if (!in_array($tableId, $this->calls)) {
                $this->calls[] = $tableId;
            }
        }

        Log::debug('Current calls: ', $this->calls);
    }
}; ?>

<div class="absolute transform right-3 bottom-3">
    @if (count($calls) > 0)
        <p class="mb-2 font-semibold tracking-widest text-center uppercase border-b border-black text-md">Admin <br>notifications</p>
    @endif

    <ul class="flex flex-col gap-1">
        @foreach ($calls as $call)
            <li>
                <livewire:notification-message :tableId="$call" status="Call" />
            </li>
        @endforeach
    </ul>
</div>
