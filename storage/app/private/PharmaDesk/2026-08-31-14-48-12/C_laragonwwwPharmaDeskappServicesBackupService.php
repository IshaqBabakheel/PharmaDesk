<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\BackupDestination\BackupDestination;

class BackupService
{

    // public function create(): string
    // {
    //     $tempDir = storage_path('app/backup-temp');
    //     if (!is_dir($tempDir)) {
    //         mkdir($tempDir, 0775, true);
    //     }

    //     // 1. Manually dump the database using mysqldump
    //     $dumpFile = $tempDir . '/database.sql';
    //     $mysqldump = 'C:/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysqldump.exe'; // adjust if needed

    //     $dumpCommand = [
    //         $mysqldump,
    //         '--host=127.0.0.1',
    //         '--port=3306',
    //         '--protocol=TCP',
    //         '--user=root',
    //         '--password=',
    //         '--single-transaction',
    //         'pharmadesk',
    //         '--result-file=' . $dumpFile,
    //     ];

    //     $dumpProcess = new Process($dumpCommand);
    //     $dumpProcess->setTimeout(600);
    //     $dumpProcess->run();

    //     if (!$dumpProcess->isSuccessful()) {
    //         throw new \RuntimeException(
    //             'Database dump failed: ' . $dumpProcess->getErrorOutput()
    //         );
    //     }

    //     // 2. Now run the Spatie backup (but skip database dumping)
    //     // We'll use a modified environment to tell Spatie not to dump databases.
    //     $env = $_SERVER;
    //     $env['DB_CONNECTION'] = ''; // trick: set to empty so no databases are dumped

    //     $process = new Process([
    //         PHP_BINARY,
    //         base_path('artisan'),
    //         'backup:run',
    //     ], base_path());

    //     $process->setEnv($env);
    //     $process->setTimeout(600);
    //     $process->run();

    //     if (!$process->isSuccessful()) {
    //         throw new \RuntimeException(
    //             trim($process->getErrorOutput() ?: $process->getOutput() ?: 'Backup failed.')
    //         );
    //     }

    //     // Optional: clean up the manual dump file after backup
    //     // unlink($dumpFile);

    //     return trim($process->getOutput());
    // }

    // public function create(): string
    // {
    //     $process = new Process([
    //         PHP_BINARY,
    //         base_path('artisan'),
    //         'backup:run',
    //     ], base_path());

    //     $process->setTimeout(600);

    //     $process->run();

    //     if (!$process->isSuccessful()) {
    //         throw new \RuntimeException(
    //             trim(
    //                 $process->getErrorOutput()
    //                 ?: $process->getOutput()
    //                 ?: 'Backup failed.'
    //             )
    //         );
    //     }

    //     return trim(
    //         $process->getOutput()
    //     );
    // }


    public function create(): string
    {
        $php = env(
            'PHP_CLI_BINARY',
            PHP_BINARY
        );

        $process = new Process([
            $php,
            base_path('artisan'),
            'backup:run',
        ], base_path());

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

        return trim(
            $process->getOutput()
        );
    }

    public function backups(): Collection
    {
        $diskNames = config('backup.backup.destination.disks', ['local']);

        return collect($diskNames)
            ->flatMap(function ($diskName) {

                $destination =
                    BackupDestination::create(
                        config('backup.backup.name'),
                        $diskName
                    );

                return collect(
                    $destination->backups()
                )->map(function ($backup) {

                    $size = $backup->sizeInBytes();

                    return [
                        'path' => $backup->path(),
                        'filename' => basename($backup->path()),
                        'size' => $size,
                        'size_human' =>  $this->humanSize($size),
                        'date' => $backup->date()?->format('d M Y H:i'),
                    ];
                });
            })
            ->sortByDesc('date')
            ->values();
    }

    public function download(string $filename)
    {
        $backup =
            $this->findBackup($filename);

        abort_unless($backup, 404);

        return response()->download(
            $backup->path()
        );
    }

    public function delete(string $filename): void
    {
        $backup =
            $this->findBackup($filename);

        abort_unless($backup, 404);

        $backup->delete();
    }

    protected function findBackup(string $filename) 
    {
        foreach (
            config(
                'backup.backup.destination.disks',
                ['local']
            ) as $diskName
        ) {

            $destination =
                BackupDestination::create(
                    config('backup.backup.name'),
                    $diskName
                );

            $backup =
                collect(
                    $destination->backups()
                )->first(
                    fn ($backup) =>
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

    protected function humanSize(int|float $bytes): string 
    {
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
                log($bytes, 1024)
            ),
            count($units) - 1
        );

        return number_format(
            $bytes / pow(1024, $index),
            2
        ) . ' ' . $units[$index];
    }
}