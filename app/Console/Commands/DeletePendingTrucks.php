<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TruckDetals;
use Carbon\Carbon;

class DeletePendingTrucks extends Command
{
    protected $signature = 'trucks:delete-pending';
    protected $description = 'Delete pending truck records older than 5 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subMinutes(5); // 5 minutes pehle ka time
        $deleted = TruckDetals::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("$deleted pending truck records deleted.");
    }
}
