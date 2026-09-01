<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function __construct(
        protected BackupService $backupService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('backups.view'),
            403
        );

        $backups = $this->backupService->backups();

        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        abort_unless(
            auth()->user()->can('backups.create'),
            403
        );

        try {

            $this->backupService->create();

            return back()->with(
                'success',
                'Backup created successfully.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to create backup.' . $e->getMessage()
            );
        }
    }

    public function download(string $filename)
    {
        abort_unless(
            auth()->user()->can('backups.download'),
            403
        );

        return $this->backupService->download($filename);
    }

    public function destroy(string $filename)
    {
        abort_unless(
            auth()->user()->can('backups.delete'),
            403
        );

        try {

            $this->backupService->delete($filename);

            return back()->with(
                'success',
                'Backup deleted successfully.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to delete backup.' . $e->getMessage()
            );
        }
    }
}