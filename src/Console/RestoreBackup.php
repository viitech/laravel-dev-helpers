<?php

namespace VIITech\Helpers\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use VIITech\Helpers\GlobalHelpers;
use ZanySoft\Zip\Zip;
use ZanySoft\Zip\ZipManager;

/**
 * Class RestoreBackup
 * @package VIITech\Helpers\Console
 */
class RestoreBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore {file_name} {--download} {--production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $production = $this->option("production");
        $download = $this->option("download");

        if($download){
            if($production){
                Artisan::call("backup:download latest --production --silent");
            }else{
                Artisan::call("backup:download latest --silent");
            }
        }

        $file_path = $this->argument("file_name");
        if($file_path == "latest") {
            $backup_files = Storage::disk("local")->files("backups");
            $backup_file_index = Helpers::findFileInArray($backup_files);
            if(is_null($backup_file_index)){
                dd("Unable to find the backup file");
            }
            $file_path = $backup_files[$backup_file_index];
        }

        if(!Str::contains($file_path, "backups")){
            $file_path = "backups/$file_path";
        }

        $backup_file = $file_path;
        if(!Storage::disk("local")->exists($file_path)){
            dd("Unable to find the file");
        }

        $ask = $this->ask('Are you sure?');
        if(!GlobalHelpers::convertStringToBoolean($ask)){
            dd("Okay");
        }

        $file_full_path = storage_path("app/$file_path");

        // unzip
        $ask = $this->ask('Do you want to run unzip command?');
        if(GlobalHelpers::convertStringToBoolean($ask)){

            // extract file
            $manager = new ZipManager();
            $manager->addZip(Zip::open($file_full_path));
            $manager->extract(storage_path("app/backups"), false);

            // find file
            $files = Storage::disk("local")->files("backups/db-dumps");
            $file_index = $this->findFileInArray($files);
            if(!is_null($file_index)){
                $file_path = $files[$file_index];
            }
        }

        if(is_null($file_path)){
            dd("Unable to find the file");
        }

        // gz decode
        $ask = $this->ask('Do you want to run gzdecode command?');
        if(GlobalHelpers::convertStringToBoolean($ask)){
            $file = Storage::disk("local")->get($file_path);
            $file_path = str_replace(".sql.gz", ".sql", $file_path);
            $decompressed = gzdecode($file);
            Storage::disk("local")->put($file_path, $decompressed);
        }

        // get file path
        $file_full_path = Storage::disk("local")->path($file_path);
        if(Str::contains($file_full_path, ".zip") || Str::contains($file_full_path, ".gz")){
            dd("Compress the file first");
        }

        // import
        $file_full_path = storage_path("app/$file_path");
        $done = DB::unprepared(file_get_contents($file_full_path));
        if($done){
            $this->info("Database imported successfully");
        }else{
            $this->info("Unable to import the database");
        }

        // delete the folder
        $folder = str_replace(basename($file_path), "", $file_path);
        $folder = str_replace(storage_path("app/"), "", $folder);
        Storage::disk("local")->deleteDirectory($folder);

        $ask = $this->ask('Do you want to delete the backup file?');
        if(GlobalHelpers::convertStringToBoolean($ask)){
            Storage::disk("local")->delete($backup_file);
        }
    }

    /**
     * Find File in Array
     * @return string|null
     */
    function findFileInArray($files){
        $file_index = null;
        foreach ($files as $key => $file){
            if(Str::contains($file, ".gz")){
                $file_index = $key;
                break;
            }
        }
        if(is_null($file_index)){
            foreach ($files as $key => $file){
                if(Str::contains($file, ".sql")){
                    $file_index = $key;
                    break;
                }
            }
        }
        if(is_null($file_index)){
            foreach ($files as $key => $file){
                if(Str::contains($file, ".zip")){
                    $file_index = $key;
                    break;
                }
            }
        }
        return $file_index;
    }
}
