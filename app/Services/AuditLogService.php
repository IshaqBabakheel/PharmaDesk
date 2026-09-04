<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

class AuditLogService
{
    public function record(string $description, ?Model $subject = null, array $properties = [], ?string $logName = null, ?string $event = null): ActivityContract 
    {
        $logger = $logName
            ? activity($logName)
            : activity();

        if ($subject) {
            $logger->performedOn($subject);
        }

        if (auth()->check()) {
            $logger->causedBy(auth()->user());
        }

        if ($event !== null) {
            $logger->event($event);
        }

        if ($properties) {
            $logger->withProperties($properties);
        }

        return $logger->log($description);
    }

    public function query(array $filters = []): Builder
    {
        return Activity::query()
            ->with([
                'causer',
                'subject',
            ])
            ->when(
                $filters['user_id'] ?? null,
                fn(Builder $query, $userId) =>
                $query->where('causer_id', $userId)
            )
            ->when(
                $filters['log_name'] ?? null,
                fn(Builder $query, $logName) =>
                $query->where('log_name', $logName)
            )
            ->when(
                $filters['event'] ?? null,
                fn(Builder $query, $event) =>
                $query->where('event', $event)
            )
            ->when(
                $filters['subject_type'] ?? null,
                fn(Builder $query, $subjectType) =>
                $query->where('subject_type', $subjectType)
            )
            ->when(
                $filters['subject_id'] ?? null,
                fn(Builder $query, $subjectId) =>
                $query->where('subject_id', $subjectId)
            )
            ->when(
                $filters['date_from'] ?? null,
                fn(Builder $query, $date) =>
                $query->whereDate('created_at', '>=', $date)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn(Builder $query, $date) =>
                $query->whereDate('created_at', '<=', $date)
            )
            ->when(
                $filters['search'] ?? null,
                function (Builder $query, $search) {
                    $search = trim($search);

                    $query->where(function (Builder $query) use ($search) {
                        $query
                            ->where('description', 'like', "%{$search}%")
                            ->orWhere('log_name', 'like', "%{$search}%")
                            ->orWhere('event', 'like', "%{$search}%");
                    });
                }
            );
    }

    public function findOrFail(int|string $id): Activity
    {
        return Activity::query()
            ->with([
                'causer',
                'subject',
            ])
            ->findOrFail($id);
    }
}
