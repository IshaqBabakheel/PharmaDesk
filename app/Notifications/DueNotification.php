<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class DueNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $partyType,
        public int $partyId,
        public string $partyName,
        public float $amount,
        public string $referenceNumber
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $isCustomer = $this->partyType === 'customer';

        return new DatabaseMessage([
            'type' => $this->partyType . '_due',
            'title' => $isCustomer
                ? 'Customer Due'
                : 'Supplier Due',
            'message' => sprintf(
                '%s has an outstanding balance of Rs. %s.',
                $this->partyName,
                number_format($this->amount, 2)
            ),
            'party_type' => $this->partyType,
            'party_id' => $this->partyId,
            'party_name' => $this->partyName,
            'amount' => $this->amount,
            'reference_number' => $this->referenceNumber,
            'url' => $isCustomer
                ? route('customers.show', $this->partyId)
                : route('suppliers.show', $this->partyId),
            'fingerprint' => sprintf(
                '%s_due:%s:%.2f',
                $this->partyType,
                $this->partyId,
                $this->amount
            ),
        ]);
    }
}