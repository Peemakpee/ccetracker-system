<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PerformDataRetention extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:perform-data-retention';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform data retention for documents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    }
    private function performDataRetentionForTable($tableModel)
    {
        $now = Carbon::now();
        $tableModel::where('retention_date', '<=', $now)->chunk(200, function ($documents) {
            foreach ($documents as $document) {
            }
        });
    }
}
