<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\UploadFile;
use App\Models\ApprovedChanges;
use App\Models\ApprovedDean;
use App\Models\ApprovedPh;
use App\Models\CompliedChanges;
use App\Models\ArchiveUploads;
use App\Models\Retention;

class DeleteOldFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-files';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete files older than 2 years';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now();
        $twoYearsAgo = $currentDate->subYears(2);

        $this->moveToRetention(UploadFile::class, 'file_path', 'file_name', 'firebase_url', $twoYearsAgo);
        $this->moveToRetention(ApprovedPh::class, 'file_path', 'file_name', 'firebaseUrl_wSign', $twoYearsAgo);
        $this->moveToRetention(ApprovedDean::class, 'file_path', 'file_name', 'firebaseUrl_wSignDean', $twoYearsAgo);
        $this->moveToRetention(ApprovedChanges::class, 'file_path', 'file_name', 'firebaseUrl_changes', $twoYearsAgo);
        $this->moveToRetention(CompliedChanges::class, 'file_path', 'file_name', 'firebaseUrl_complied', $twoYearsAgo);

        UploadFile::where('created_at', '<', $twoYearsAgo)->delete();
        ApprovedPh::where('created_at', '<', $twoYearsAgo)->delete();
        ApprovedDean::where('created_at', '<', $twoYearsAgo)->delete();
        ApprovedChanges::where('created_at', '<', $twoYearsAgo)->delete();
        CompliedChanges::where('created_at', '<', $twoYearsAgo)->delete();
        ArchiveUploads::where('created_at', '<', $twoYearsAgo)->delete();

        $this->info('Old files deleted and moved to the retention table successfully.');
    }

    /**
     * Move files to the retention table
     *
     * @param string $model
     * @param string $filePathColumn
     * @param string $fileNameColumn
     * @param string $firebaseUrlColumn
     * @param \Carbon\Carbon $twoYearsAgo
     * @return void
     */
    protected function moveToRetention($model, $filePathColumn, $fileNameColumn, $firebaseUrlColumn, $twoYearsAgo)
    {
        $files = $model::where('created_at', '<', $twoYearsAgo)->get();

        foreach ($files as $file) {
            Retention::create([
                'file_path' => $file->$filePathColumn,
                'file_name' => $file->$fileNameColumn,
                'firebase_url' => $file->$firebaseUrlColumn,
            ]);
        }
    }
}
