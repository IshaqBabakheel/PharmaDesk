<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\BackupDestination\BackupDestination;

class BackupService
{


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
                        $diskName,
                        config('backup.backup.name')
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
        $backup = $this->findBackup($filename);

        abort_unless($backup, 404);

        $path = $backup->disk()->path($backup->path());

        abort_unless(
            file_exists($path),
            404,
            'Backup file not found.'
        );

        return response()->download(
            $path,
            basename($backup->path()),
            [
                'Content-Type' => 'application/zip',
            ]
        );
    }


    public function delete(string $filename): void
    {
        $backup = $this->findBackup($filename);

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
                    $diskName,
                    config('backup.backup.name')
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