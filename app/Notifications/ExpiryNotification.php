<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $medicineId,
        public string $medicineName,
        public string $batchNumber,
        public string $expiryDate,
        public int $daysRemaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'expiry',
            'title' => 'Medicine Expiry Alert',
            'message' => "{$this->medicineName} batch {$this->batchNumber} expires in {$this->daysRemaining} days.",
            'medicine_id' => $this->medicineId,
            'medicine_name' => $this->medicineName,
            'batch_number' => $this->batchNumber,
            'expiry_date' => $this->expiryDate,
            'days_remaining' => $this->daysRemaining,
            'url' => route('medicines.show', $this->medicineId),
            'fingerprint' => "expiry:{$this->medicineId}:{$this->batchNumber}:{$this->expiryDate}",
        ]);
    }
}