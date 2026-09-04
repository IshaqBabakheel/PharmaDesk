<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// class BackupController extends Controller
// {
//     public function __construct(
//         protected BackupService $backupService
//     ) {}

//     public function index()
//     {
//         abort_unless(
//             auth()->user()->can('backups.view'),
//             403
//         );

//         $backups = $this->backupService->backups();

//         return view('backups.index', compact('backups'));
//     }

//     public function create()
//     {
//         abort_unless(
//             auth()->user()->can('backups.create'),
//             403
//         );

//         try {

//             $this->backupService->create();

//             return back()->with(
//                 'success',
//                 'Backup created successfully.'
//             );

//         } catch (\Throwable $e) {

//             report($e);

//             return back()->with(
//                 'error',
//                 'Unable to create backup.' . $e->getMessage()
//             );
//         }
//     }

//     public function download(string $filename)
//     {
//         abort_unless(
//             auth()->user()->can('backups.download'),
//             403
//         );

//         return $this->backupService->download($filename);
//     }

//     public function destroy(string $filename)
//     {
//         abort_unless(
//             auth()->user()->can('backups.delete'),
//             403
//         );

//         try {

//             $this->backupService->delete($filename);

//             return back()->with(
//                 'success',
//                 'Backup deleted successfully.'
//             );

//         } catch (\Throwable $e) {

//             report($e);

//             return back()->with(
//                 'error',
//                 'Unable to delete backup.' . $e->getMessage()
//             );
//         }
//     }
// }



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

        $backups =
            $this->backupService->backups();

        $statistics =
            $this->backupService->statistics();

        return view(
            'backups.index',
            compact(
                'backups',
                'statistics'
            )
        );
    }

    public function create(): JsonResponse
    {
        abort_unless(
            auth()->user()->can('backups.create'),
            403
        );

        try {

            $this->backupService->create();

            return response()->json([
                'success' => true,
                'message' =>
                'Backup created successfully.',
            ]);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                'Unable to create backup.',
            ], 500);
        }
    }

    public function show(
        string $filename
    ): JsonResponse {

        abort_unless(
            auth()->user()->can('backups.details'),
            403
        );

        try {

            return response()->json([
                'success' => true,
                'data' =>
                $this->backupService
                    ->details($filename),
            ]);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                'Unable to load backup details.',
            ], 500);
        }
    }

    public function download(
        string $filename
    ) {

        abort_unless(
            auth()->user()->can('backups.download'),
            403
        );

        return $this->backupService
            ->download($filename);
    }

    public function destroy(
        string $filename
    ): JsonResponse {

        abort_unless(
            auth()->user()->can('backups.delete'),
            403
        );

        try {

            $this->backupService
                ->delete($filename);

            return response()->json([
                'success' => true,
                'message' =>
                'Backup deleted successfully.',
            ]);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                'Unable to delete backup.',
            ], 500);
        }
    }

    public function restore(
        string $filename
    ): JsonResponse {

        abort_unless(
            auth()->user()->can('backups.restore'),
            403
        );

        try {

            $result =
                $this->backupService
                ->restore($filename);

            if (!$result['success']) {

                return response()->json(
                    $result,
                    500
                );
            }

            return response()->json(
                $result
            );
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                'Unable to restore the selected backup.',
            ], 500);
        }
    }

    public function cleanup(): JsonResponse
    {
        abort_unless(
            auth()->user()->can('backups.cleanup'),
            403
        );

        try {

            $this->backupService
                ->cleanup();

            return response()->json([
                'success' => true,
                'message' =>
                'Backup cleanup completed successfully.',
            ]);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                'Unable to complete backup cleanup.',
            ], 500);
        }
    }
}
