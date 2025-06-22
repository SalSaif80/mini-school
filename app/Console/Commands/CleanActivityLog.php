<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class CleanActivityLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitylog:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean activity log entries older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $deletedCount = Activity::where('created_at', '<', $thirtyDaysAgo)->delete();

        $this->info("Deleted {$deletedCount} activity log entries older than 30 days.");

        return 0;
    }
}
