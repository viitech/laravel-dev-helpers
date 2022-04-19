<?php

namespace VIITech\Helpers\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use VIITech\Helpers\GlobalHelpers;

/**
 * Class DownloadBackupCommand
 * @package VIITech\Helpers\Console
 */
class DownloadBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:download {name} {--production} {--silent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Backup';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws FileNotFoundException
     */
    public function handle()
    {

        $name = $this->argument("name");
        $production = $this->option("production");
        $silent = $this->option("silent");
        $env = $production ? "production" : null;

        if($name == "latest"){
            $backup_folder = config('backup.backup.name');
            if(empty($backup_folder)){
                $backup_folder = GlobalHelpers::getApplicationNameWithEnv($env);
            }
            $list = Storage::disk("s3_backup")->allFiles($backup_folder);
            $name = collect($list)->last();
        }

        if(is_null($name)){
            dd("Unable to find the backup file remotely");
        }

        $file_name = "backups/" . basename($name);
        $file = Storage::disk("local")->exists($file_name);
        if($file && !$silent){
            dd("File already exists locally storage/" . $file_name);
        }

        if(!$silent){
            $ask = $this->ask('Are you sure?');
            if($ask != "y" && $ask != "yes" && $ask != "ee") {
                dd("Okay");
            }
        }

        $file = Storage::disk("s3_backup")->get($name);
        if(!$file){
            dd("File doesnt exist in S3");
        }

        $downloaded = Storage::disk("local")->put($file_name, $file);
        if($downloaded){
            $this->info("File is downloaded to storage/" . $file_name);
        }else{
            $this->info("Couldn't download the file!");
        }

        return true;
    }
}
