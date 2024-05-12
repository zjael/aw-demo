<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Reverb\Events\MessageReceived;
use App\Events\CallForWaiterStatus;
use App\Enums\CallEventStatusEnum;

/**
 *  Reverb uses it own event "MessageReceived" to listen to the incoming messages
 */
class SocketListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageReceived $event): void
    {
        $message = $event->message ? json_decode($event->message) : null;
        $type = $message ? $message->event : null;
        $data = $message ? $message->data : null;
        if (is_string($data)) {
            $data = json_decode($data);
        }

        if ($type === 'CallForWaiter') {
            $tableId = $data->tableId;
            Log::debug('A new waiter call for table:' . $tableId);
            CallForWaiterStatus::dispatch(CallEventStatusEnum::PENDING->value, $tableId);
        }

        if ($type === 'CallForWaiterStatus') {
            $tableId = $data->tableId;
            $status = $data->status;
            Log::debug('Waiter status changed for the table: ' . $tableId . ' to ' . $status);
            CallForWaiterStatus::dispatch($status, $tableId);
        }
    }
}

