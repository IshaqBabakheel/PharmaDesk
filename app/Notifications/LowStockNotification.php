<?php

namespace App\Notifications;

use App\Models\Medicine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Medicine $medicine
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'low_stock',
            'title' => 'Low Stock Alert',
            'message' => "{$this->medicine->name} is low on stock.",
            'medicine_id' => $this->medicine->id,
            'medicine_code' => $this->medicine->medicine_code,
            'sku' => $this->medicine->sku,
            'current_stock' => (int) $this->medicine->current_stock,
            'reorder_level' => (int) $this->medicine->reorder_level,
            'url' => route('medicines.show', $this->medicine),
            'fingerprint' => "low_stock:medicine:{$this->medicine->id}",
        ]);
    }
}