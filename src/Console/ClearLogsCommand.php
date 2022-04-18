<?php

namespace VIITech\Helpers\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class ClearLogsCommand
 * @package VIITech\Helpers\Console
 */
class ClearLogsCommand extends Command
{
    protected $signature = 'clear:logs {--keep-last : Whether the last log file should be kept}';

    protected $description = 'Clear Logs Command';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = $this->getLogFiles();

        if ($this->option('keep-last') && $files->count() >= 1) {
            $files->shift();
        }

        $deleted = $this->delete($files);

        if (! $deleted) {
            $this->info('There was no log file to delete in the log folder');
        } elseif ($deleted == 1) {
            $this->info('1 log file has been deleted');
        } else {
            $this->info($deleted . ' log files have been deleted');
        }

        Storage::disk("logs")->put("laravel.log", "");
    }

    /**
     * Get a collection of log files sorted by their last modification date.
     */
    private function getLogFiles(): Collection
    {
        return Collection::make(
            Storage::disk("logs")->allFiles()
        )->sortBy('mtime');
    }

    /**
     * Delete the given files.
     */
    private function delete(Collection $files): int
    {
        return $files->each(function ($file) {
            Storage::disk("logs")->delete($file);
        })->count();
    }
}
