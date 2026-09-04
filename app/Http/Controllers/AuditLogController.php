<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('audit-logs.view'),
            403
        );

        $users = User::query()
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $events = Activity::query()
            ->whereNotNull('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event');

        $logNames = Activity::query()
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        $subjectTypes = Activity::query()
            ->whereNotNull('subject_type')
            ->distinct()
            ->orderBy('subject_type')
            ->pluck('subject_type');

        return view('audit_logs.index', compact(
            'users',
            'events',
            'logNames',
            'subjectTypes'
        ));
    }

    public function datatable(Request $request)
    {
        abort_unless(
            auth()->user()->can('audit-logs.view'),
            403
        );

        $activities = $this->auditLogService
            ->query($request->only([
                'user_id',
                'event',
                'log_name',
                'subject_type',
                'subject_id',
                'date_from',
                'date_to',
                'search',
            ]))
            ->latest('id')
            ->get();

        return response()->json([
            'data' => $activities->map(function (Activity $activity) {
                return [
                    'id' => $activity->id,

                    'date' => $activity->created_at?->format(
                        'd M Y h:i A'
                    ),

                    'date_sort' => $activity->created_at?->timestamp ?? 0,

                    'user' => $activity->causer?->name ?? 'System',

                    'event' => $activity->event ?: 'activity',

                    'log_name' => $activity->log_name ?: 'default',

                    'module' => $activity->subject_type
                        ? class_basename($activity->subject_type)
                        : 'System',

                    'record' => $this->recordLabel($activity),

                    'description' => $activity->description,

                    'view_url' => route(
                        'audit-logs.show',
                        $activity->id
                    ),
                ];
            })->values(),
        ]);
    }

    public function show(int|string $id)
    {
        abort_unless(
            auth()->user()->can('audit-logs.view'),
            403
        );

        $activity = $this->auditLogService->findOrFail($id);

        return view('audit_logs.show', compact('activity'));
    }

    protected function recordLabel(Activity $activity): string
    {
        $subject = $activity->subject;

        if ($subject) {
            foreach ([
                'invoice_number',
                'purchase_number',
                'return_number',
                'payment_number',
                'expense_number',
                'adjustment_number',
                'medicine_code',
                'customer_number',
                'supplier_number',
                'sku',
                'barcode',
                'name',
                'title',
            ] as $field) {
                if (
                    isset($subject->{$field}) &&
                    filled($subject->{$field})
                ) {
                    return (string) $subject->{$field};
                }
            }

            return "#{$subject->getKey()}";
        }

        /*
         * Fallback for subjects that can no longer be resolved,
         * for example permanently deleted records.
         */
        $properties = $activity->properties ?? collect();

        foreach ([
            'invoice_number',
            'purchase_number',
            'return_number',
            'payment_number',
            'expense_number',
            'adjustment_number',
            'medicine_code',
            'customer_number',
            'supplier_number',
            'sku',
            'barcode',
            'name',
            'title',
        ] as $field) {
            $value = $properties->get($field);

            if (filled($value)) {
                return (string) $value;
            }
        }

        if ($activity->subject_type && $activity->subject_id) {
            return "#{$activity->subject_id}";
        }

        return '—';
    }
}