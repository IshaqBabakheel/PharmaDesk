<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ExpiredMedicineNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $medicineId,
        public string $medicineName,
        public string $batchNumber,
        public string $expiryDate
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'expired',
            'title' => 'Expired Medicine',
            'message' => "{$this->medicineName} batch {$this->batchNumber} has expired.",
            'medicine_id' => $this->medicineId,
            'medicine_name' => $this->medicineName,
            'batch_number' => $this->batchNumber,
            'expiry_date' => $this->expiryDate,
            'url' => route('medicines.show', $this->medicineId),
            'fingerprint' => "expired:{$this->medicineId}:{$this->batchNumber}:{$this->expiryDate}",
        ]);
    }
}