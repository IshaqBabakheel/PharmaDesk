<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Illuminate\Support\Collection;
use Spatie\Backup\BackupDestination\BackupDestination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BackupService
{
    /**
     * Create a full backup using the project's CLI PHP binary.
     */
    public function create(): string
    {
        $php = PHP_BINARY;

        /*
     * Pass the complete current process environment to the
     * child PHP process, then explicitly enforce the two
     * desktop-specific runtime paths.
     *
     * This is important because Electron provides these
     * variables when it starts Laravel.
     */
        $environment = [];

        foreach (getenv() as $key => $value) {
            if (is_string($value)) {
                $environment[$key] = $value;
            }
        }

        $environment['LARAVEL_STORAGE_PATH'] = storage_path();

        $dumpBinaryPath = getenv('DB_DUMP_BINARY_PATH');

        if (is_string($dumpBinaryPath) && $dumpBinaryPath !== '') {
            $environment['DB_DUMP_BINARY_PATH'] = $dumpBinaryPath;
        }

        $process = new Process(
            [
                $php,
                base_path('artisan'),
                'backup:run',
            ],
            base_path(),
            $environment
        );

        $process->setTimeout(600);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                trim(
                    $process->getErrorOutput()
                        ?: $process->getOutput()
                        ?: 'Backup failed.'
                )
            );
        }

        return trim($process->getOutput());
    }

    /**
     * Create a database-only safety backup before a restore.
     */
    public function createDatabaseSafetyBackup(): string
    {
        $php = PHP_BINARY;

        $environment = [];

        foreach (getenv() as $key => $value) {
            if (is_string($value)) {
                $environment[$key] = $value;
            }
        }

        $environment['LARAVEL_STORAGE_PATH'] = storage_path();

        $dumpBinaryPath = getenv('DB_DUMP_BINARY_PATH');

        if (is_string($dumpBinaryPath) && $dumpBinaryPath !== '') {
            $environment['DB_DUMP_BINARY_PATH'] = $dumpBinaryPath;
        }

        $process = new Process(
            [
                $php,
                base_path('artisan'),
                'backup:run',
                '--only-db',
            ],
            base_path(),
            $environment
        );

        $process->setTimeout(600);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                trim(
                    $process->getErrorOutput()
                        ?: $process->getOutput()
                        ?: 'Database safety backup failed.'
                )
            );
        }

        return trim($process->getOutput());
    }

    /**
     * Return all available backups.
     */
    public function backups(): Collection
    {
        $diskNames = config(
            'backup.backup.destination.disks',
            ['local']
        );

        // $backups = collect($diskNames)
        return collect($diskNames)
            ->flatMap(function (string $diskName) {

                $destination = BackupDestination::create(
                    $diskName,
                    config('backup.backup.name')
                );

                return collect(
                    $destination->backups()
                )->map(function ($backup) use ($diskName) {

                    $size = $backup->sizeInBytes();
                    $date = $backup->date();

                    return [
                        'path' => $backup->path(),

                        'filename' =>
                        basename($backup->path()),

                        'disk' =>
                        $diskName,

                        'size' =>
                        $size,

                        'size_human' =>
                        $this->humanSize($size),

                        'date' =>
                        $date?->format('d M Y H:i'),

                        'date_iso' =>
                        $date?->toIso8601String(),

                        'age_human' =>
                        $date?->diffForHumans(),

                        // 'is_latest' => false,

                        'exists' =>
                        (bool) $backup->exists,

                        'backup' =>
                        $backup,
                    ];
                });
            })
            ->sortByDesc('date_iso')
            ->values();


        //     if ($backups->isNotEmpty()) {
        //         $backups[0]['is_latest'] = true;
        //     }

        // return $backups;
    }

    /**
     * Dashboard statistics.
     */
    public function statistics(): array
    {
        $backups = $this->backups();
        $latest = $backups->first();
        $oldest = $backups->last();

        $totalBytes = $backups->sum('size');

        return [
            'total_backups' =>
            $backups->count(),

            'total_storage' =>
            $this->humanSize($totalBytes),

            'latest_backup' =>
            $latest,

            'latest_size' =>
            $latest['size_human'] ?? '0 B',

            'oldest_backup' =>
            $oldest,

            'average_size' =>
            $backups->isEmpty()
                ? '0 B'
                : $this->humanSize(
                    $totalBytes /
                        $backups->count()
                ),

            'health' =>
            $this->health($latest),
        ];
    }

    /**
     * Return health information for the latest backup.
     */
    public function health(?array $latest = null): array
    {
        $latest ??= $this->backups()->first();

        if (!$latest || empty($latest['date_iso'])) {
            return [
                'status' => 'critical',
                'label' => 'Critical',
                'message' => 'No backup is available.',
            ];
        }

        $date = Carbon::parse(
            $latest['date_iso']
        );

        $ageHours = $date->diffInHours(now());

        $healthyHours = max(
            1,
            (int) env(
                'BACKUP_HEALTH_MAX_AGE_HOURS',
                24
            )
        );

        $warningMultiplier = max(
            1,
            (int) env(
                'BACKUP_HEALTH_WARNING_MULTIPLIER',
                2
            )
        );

        $warningHours =
            $healthyHours *
            $warningMultiplier;

        if ($ageHours <= $healthyHours) {
            return [
                'status' => 'healthy',
                'label' => 'Healthy',
                'message' =>
                'Your latest backup is recent.',
            ];
        }

        if ($ageHours <= $warningHours) {
            return [
                'status' => 'warning',
                'label' => 'Warning',
                'message' =>
                'Your latest backup is getting old.',
            ];
        }

        return [
            'status' => 'critical',
            'label' => 'Critical',
            'message' =>
            'Your latest backup is overdue.',
        ];
    }

    /**
     * Get backup details.
     */
    public function details(string $filename): array
    {
        $backup = $this->findBackup($filename);

        abort_unless(
            $backup,
            404,
            'Backup not found.'
        );

        $date = $backup->date();
        $size = $backup->sizeInBytes();

        return [
            'filename' =>
            basename($backup->path()),

            'path' =>
            $backup->path(),

            'disk' =>
            $this->findBackupDisk($filename),

            'size' =>
            $size,

            'size_human' =>
            $this->humanSize($size),

            'date' =>
            $date?->format('d M Y H:i:s'),

            'age_human' =>
            $date?->diffForHumans(),

            'exists' =>
            (bool) $backup->exists,

            'database_included' =>
            true,

            'application_files_included' =>
            true,
        ];
    }

    /**
     * Download a backup.
     */
    public function download(string $filename)
    {
        $backup = $this->findBackup($filename);

        abort_unless(
            $backup,
            404,
            'Backup not found.'
        );

        $path = $backup
            ->disk()
            ->path(
                $backup->path()
            );

        abort_unless(
            is_file($path) &&
                is_readable($path),
            404,
            'Backup file not found.'
        );

        return response()->download(
            $path,
            basename($backup->path()),
            [
                'Content-Type' =>
                'application/zip',
            ]
        );
    }

    /**
     * Delete a backup.
     */
    public function delete(string $filename): void
    {
        $backup = $this->findBackup($filename);

        $allBackups = $this->backups();

        if ($allBackups->count() <= 1) {
            throw new \RuntimeException(
                'This is the only available backup. Create another backup before deleting this one.'
            );
        }
        abort_unless(
            $backup,
            404,
            'Backup not found.'
        );

        $backup->delete();
    }

    /**
     * Restore database from a Spatie backup.
     *
     * A safety backup of the current database is created first.
     */
    public function restore(string $filename): array
    {
        $backup = $this->findBackup($filename);

        abort_unless(
            $backup,
            404,
            'Backup not found.'
        );

        $path = $backup
            ->disk()
            ->path(
                $backup->path()
            );

        abort_unless(
            is_file($path) &&
                is_readable($path),
            404,
            'Backup file not found.'
        );

        $this->validateBackupArchive($path);

        $disk = $this->findBackupDisk($filename);

        abort_unless(
            $disk,
            404,
            'Backup disk not found.'
        );

        /*
         * Step 1:
         * Create a safety backup of the CURRENT state.
         */
        $safetyBackupOutput = null;

        try {

            // $safetyBackupOutput = $this->create();
            $safetyBackupOutput = $this->createDatabaseSafetyBackup();
        } catch (\Throwable $e) {

            report($e);

            throw new \RuntimeException(
                'Restore cancelled because the current database safety backup could not be created.'
            );
        }

        /*
         * Step 2:
         * Restore using the official restore command.
         */
        $php = PHP_BINARY;

        $connection =
            config('database.default');

        $environment = [];

        foreach (getenv() as $key => $value) {
            if (is_string($value)) {
                $environment[$key] = $value;
            }
        }

        $environment['LARAVEL_STORAGE_PATH'] = storage_path();

        $dumpBinaryPath = getenv('DB_DUMP_BINARY_PATH');

        if (is_string($dumpBinaryPath) && $dumpBinaryPath !== '') {
            $environment['DB_DUMP_BINARY_PATH'] = $dumpBinaryPath;
        }

        $process = new Process(
            [
                $php,
                base_path('artisan'),
                'backup:restore',
                '--disk=' . $disk,
                '--backup=' . $backup->path(),
                '--connection=' . $connection,
                '--reset',
                '--no-interaction',
            ],
            base_path(),
            $environment
        );

        $process->setTimeout(1200);

        $process->run();

        /*
         * Step 3:
         * Restore failed.
         */
        if (!$process->isSuccessful()) {

            Log::error(
                'PharmaDesk backup restore failed.',
                [
                    'filename' =>
                    $filename,

                    'disk' =>
                    $disk,

                    'output' =>
                    $process->getOutput(),

                    'error' =>
                    $process->getErrorOutput(),
                ]
            );

            return [
                'success' => false,

                'message' =>
                'Restore failed. A safety backup of the current database was created before the restore attempt.',

                'safety_backup' =>
                $safetyBackupOutput,
            ];
        }

        // /*
        //  * Step 4:
        //  * Clear Laravel caches after the database
        //  * has been restored.
        //  */
        // $clearProcess = new Process([
        //     $php,
        //     base_path('artisan'),
        //     'optimize:clear',
        // ], base_path());

        // $clearProcess->setTimeout(120);

        // $clearProcess->run();

        // if (!$clearProcess->isSuccessful()) {

        //     Log::warning(
        //         'PharmaDesk database restored but Laravel cache clearing failed.',
        //         [
        //             'filename' =>
        //             $filename,

        //             'output' =>
        //             $clearProcess->getOutput(),

        //             'error' =>
        //             $clearProcess->getErrorOutput(),
        //         ]
        //     );
        // }

        return [
            'success' => true,

            'message' => 'Database restored successfully. Application files were not changed.',

            'safety_backup' => $safetyBackupOutput,

            'restore_output' =>
            trim(
                $process->getOutput()
            ),
        ];
    }

    /**
     * Run Spatie cleanup.
     */
    public function cleanup(): string
    {
        $php = PHP_BINARY;

        $process = new Process([
            $php,
            base_path('artisan'),
            'backup:clean',
            '--no-interaction',
        ], base_path());

        $process->setTimeout(600);

        $process->run();

        if (!$process->isSuccessful()) {

            throw new \RuntimeException(
                trim(
                    $process->getErrorOutput()
                        ?: $process->getOutput()
                        ?: 'Backup cleanup failed.'
                )
            );
        }

        return trim(
            $process->getOutput()
        );
    }

    /**
     * Validate backup archive.
     */
    protected function validateBackupArchive(
        string $path
    ): void {

        if (
            strtolower(
                pathinfo(
                    $path,
                    PATHINFO_EXTENSION
                )
            ) !== 'zip'
        ) {
            throw new \RuntimeException(
                'The selected backup is not a ZIP archive.'
            );
        }

        $zip = new \ZipArchive();

        $result = $zip->open($path);

        if ($result !== true) {

            throw new \RuntimeException(
                'The backup archive is invalid or corrupted.'
            );
        }

        $hasSql = false;

        for (
            $i = 0;
            $i < $zip->numFiles;
            $i++
        ) {

            $entry =
                $zip->getNameIndex($i);

            if (
                $entry &&
                str_ends_with(
                    strtolower($entry),
                    '.sql'
                )
            ) {
                $hasSql = true;
                break;
            }
        }

        $zip->close();

        if (!$hasSql) {

            throw new \RuntimeException(
                'The selected backup does not contain a database dump.'
            );
        }
    }

    /**
     * Find backup across configured disks.
     */
    protected function findBackup(
        string $filename
    ) {

        /*
         * We intentionally compare against basename()
         * rather than building a filesystem path from
         * user input.
         */
        foreach (
            config(
                'backup.backup.destination.disks',
                ['local']
            ) as $diskName
        ) {

            $destination =
                BackupDestination::create(
                    $diskName,
                    config(
                        'backup.backup.name'
                    )
                );

            $backup =
                collect(
                    $destination->backups()
                )->first(
                    fn($backup) =>
                    basename(
                        $backup->path()
                    ) === $filename
                );

            if ($backup) {
                return $backup;
            }
        }

        return null;
    }

    /**
     * Find configured disk for backup.
     */
    protected function findBackupDisk(
        string $filename
    ): ?string {

        foreach (
            config(
                'backup.backup.destination.disks',
                ['local']
            ) as $diskName
        ) {

            $destination =
                BackupDestination::create(
                    $diskName,
                    config(
                        'backup.backup.name'
                    )
                );

            $backup =
                collect(
                    $destination->backups()
                )->first(
                    fn($backup) =>
                    basename(
                        $backup->path()
                    ) === $filename
                );

            if ($backup) {
                return $diskName;
            }
        }

        return null;
    }

    protected function humanSize(
        int|float $bytes
    ): string {

        if ($bytes <= 0) {
            return '0 B';
        }

        $units = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
        ];

        $index = min(
            (int) floor(
                log(
                    $bytes,
                    1024
                )
            ),
            count($units) - 1
        );

        return number_format(
            $bytes /
                pow(1024, $index),
            2
        ) . ' ' . $units[$index];
    }
}
