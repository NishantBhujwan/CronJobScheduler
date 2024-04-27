<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJob;

class HitUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url:hit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hit a URL from cron job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cronJob = CronJob::first(); // Assuming there's only one record in the cron table

        if (!$cronJob) {
            $this->info("No URL found in the database.");
            return;
        }

        $lastHitTime = Carbon::parse($cronJob->last_hit);
        $interval = $cronJob->interval_in_minutes;
        $currentTime = Carbon::now();

        if ($currentTime->diffInMinutes($lastHitTime) >= $interval) {
            $client = new Client();
            $response = $client->get($cronJob->url);

            // Handle response if needed
            $statusCode = $response->getStatusCode();
            // Additional logic...

            // Update last hit time
            $cronJob->last_hit = $currentTime;
            $cronJob->save();

            $this->info("URL {$cronJob->url} hit with status code: $statusCode");
        } else {
            $this->info("URL {$cronJob->url} skipped. Interval not elapsed yet.");
        }
    }
}
