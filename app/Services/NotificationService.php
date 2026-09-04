<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\DueNotification;
use App\Notifications\ExpiredMedicineNotification;
use App\Notifications\ExpiryNotification;
use App\Notifications\LowStockNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public function unreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    public function unread(User $user, int $limit = 10): Collection
    {
        return $user->unreadNotifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function recent(User $user, int $limit = 10): Collection
    {
        return $user->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function markAsRead(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->markAsRead();

        return true;
    }

    public function markAllAsRead(User $user): int
    {
        return $user->unreadNotifications()->update([
            'read_at' => now(),
        ]);
    }

    public function generateAll(): int
    {
        $count = 0;

        $count += $this->generateLowStockNotifications();
        $count += $this->generateExpiryNotifications();
        $count += $this->generateExpiredNotifications();

        if (filter_var(
            env('NOTIFICATION_DUE_ENABLED', true),
            FILTER_VALIDATE_BOOL
        )) {
            $count += $this->generateCustomerDueNotifications();
            $count += $this->generateSupplierDueNotifications();
        }

        return $count;
    }

    public function generateLowStockNotifications(): int
    {
        $count = 0;

        $medicines = Medicine::query()
            ->where('status', true)
            ->whereColumn('current_stock', '<=', 'reorder_level')
            ->get();

        foreach ($medicines as $medicine) {
            foreach ($this->notificationUsers() as $user) {
                $fingerprint = "low_stock:medicine:{$medicine->id}";

                if ($this->alreadySentToday($user, $fingerprint)) {
                    continue;
                }

                $user->notify(new LowStockNotification($medicine));

                // $this->attachFingerprintToLatest(
                //     $user,
                //     $fingerprint
                // );

                $count++;
            }
        }

        return $count;
    }

    public function generateExpiryNotifications(): int
    {
        $count = 0;

        $days = (int) env('NOTIFICATION_EXPIRY_DAYS', 30);

        $from = now()->startOfDay();
        $to = now()->addDays($days)->endOfDay();

        $batches = PurchaseItem::query()
            ->with('medicine')
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [
                $from->toDateString(),
                $to->toDateString(),
            ])
            ->get();

        foreach ($batches as $batch) {
            $available = $this->availableBatchQuantity($batch);

            if ($available <= 0 || !$batch->medicine) {
                continue;
            }

            $expiryDate = Carbon::parse($batch->expiry_date);
            $daysRemaining = max(
                0,
                now()->startOfDay()->diffInDays(
                    $expiryDate->startOfDay(),
                    false
                )
            );

            foreach ($this->notificationUsers() as $user) {
                $fingerprint = sprintf(
                    'expiry:%s:%s:%s',
                    $batch->medicine_id,
                    $batch->batch_number,
                    $expiryDate->toDateString()
                );

                if ($this->alreadySentToday($user, $fingerprint)) {
                    continue;
                }

                $user->notify(
                    new ExpiryNotification(
                        medicineId: $batch->medicine_id,
                        medicineName: $batch->medicine->name,
                        batchNumber: $batch->batch_number,
                        expiryDate: $expiryDate->format('Y-m-d'),
                        daysRemaining: $daysRemaining
                    )
                );

                // $this->attachFingerprintToLatest(
                //     $user,
                //     $fingerprint
                // );

                $count++;
            }
        }

        return $count;
    }

    public function generateExpiredNotifications(): int
    {
        $count = 0;

        $batches = PurchaseItem::query()
            ->with('medicine')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', now()->toDateString())
            ->get();

        foreach ($batches as $batch) {
            $available = $this->availableBatchQuantity($batch);

            if ($available <= 0 || !$batch->medicine) {
                continue;
            }

            foreach ($this->notificationUsers() as $user) {
                $fingerprint = sprintf(
                    'expired:%s:%s:%s',
                    $batch->medicine_id,
                    $batch->batch_number,
                    Carbon::parse($batch->expiry_date)->toDateString()
                );

                if ($this->alreadySentToday($user, $fingerprint)) {
                    continue;
                }

                $user->notify(
                    new ExpiredMedicineNotification(
                        medicineId: $batch->medicine_id,
                        medicineName: $batch->medicine->name,
                        batchNumber: $batch->batch_number,
                        expiryDate: Carbon::parse(
                            $batch->expiry_date
                        )->format('Y-m-d')
                    )
                );

                // $this->attachFingerprintToLatest(
                //     $user,
                //     $fingerprint
                // );

                $count++;
            }
        }

        return $count;
    }

    public function generateCustomerDueNotifications(): int
    {
        $count = 0;

        $customers = Customer::query()
            ->whereNull('deleted_at')
            ->whereHas('sales', function ($query) {
                $query
                    ->where('due_amount', '>', 0)
                    ->where('status', '!=', 'cancelled');
            })
            ->get();

        foreach ($customers as $customer) {
            $due = (float) $customer->sales()
                ->where('status', '!=', 'cancelled')
                ->sum('due_amount');

            if ($due <= 0) {
                continue;
            }

            foreach ($this->notificationUsers() as $user) {
                $fingerprint = sprintf(
                    'customer_due:%s:%.2f',
                    $customer->id,
                    $due
                );

                if ($this->alreadySentToday($user, $fingerprint)) {
                    continue;
                }

                $user->notify(
                    new DueNotification(
                        partyType: 'customer',
                        partyId: $customer->id,
                        partyName: $customer->name,
                        amount: $due,
                        referenceNumber: 'Customer Balance'
                    )
                );

                // $this->attachFingerprintToLatest(
                //     $user,
                //     $fingerprint
                // );

                $count++;
            }
        }

        return $count;
    }

    public function generateSupplierDueNotifications(): int
    {
        $count = 0;

        $suppliers = Supplier::query()
            ->whereNull('deleted_at')
            ->whereHas('purchases', function ($query) {
                $query
                    ->where('due_amount', '>', 0)
                    ->where('status', '!=', 'Cancelled');
            })
            ->get();

        foreach ($suppliers as $supplier) {
            $due = (float) $supplier->purchases()
                ->where('status', '!=', 'Cancelled')
                ->sum('due_amount');

            if ($due <= 0) {
                continue;
            }

            foreach ($this->notificationUsers() as $user) {
                $fingerprint = sprintf(
                    'supplier_due:%s:%.2f',
                    $supplier->id,
                    $due
                );

                if ($this->alreadySentToday($user, $fingerprint)) {
                    continue;
                }

                $user->notify(
                    new DueNotification(
                        partyType: 'supplier',
                        partyId: $supplier->id,
                        partyName: $supplier->name,
                        amount: $due,
                        referenceNumber: 'Supplier Balance'
                    )
                );

                // $this->attachFingerprintToLatest(
                //     $user,
                //     $fingerprint
                // );

                $count++;
            }
        }

        return $count;
    }

    protected function availableBatchQuantity($batch): int
    {
        $received = (int) $batch->quantity
            + (int) ($batch->free_quantity ?? 0);

        $returned = $batch->purchaseReturnItems()
            ->sum('quantity');

        $sold = $batch->saleItems()
            ->whereHas('sale', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');

        $saleReturned = $batch->saleReturnItems()
            ->whereHas('saleReturn', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');

        return max(
            0,
            $received
            - (int) $returned
            - (int) $sold
            + (int) $saleReturned
        );
    }

    protected function notificationUsers(): Collection
    {
        return User::query()
            ->where('status', true)
            ->get();
    }

    protected function alreadySentToday(
        User $user,
        string $fingerprint
    ): bool {
        return $user->notifications()
            ->where('created_at', '>=', now()->startOfDay())
            ->where('data->fingerprint', $fingerprint)
            ->exists();
    }

    // protected function attachFingerprintToLatest(User $user,string $fingerprint): void 
    // {
    //     $notification = $user->notifications()
    //         ->latest()
    //         ->first();

    //     if (!$notification) {
    //         return;
    //     }

    //     $data = $notification->data ?? [];
    //     $data['fingerprint'] = $fingerprint;

    //     $notification->update([
    //         'data' => $data,
    //     ]);
    // }
}