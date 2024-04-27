<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJob;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

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

        $interval = $cronJob->interval_in_minutes;

        // Get the last hit time from cache or default to null
        $lastHitTime = Cache::get('last_hit_time_' . $cronJob->id);

        // Check if the URL has never been hit or if the interval has elapsed
        if (!$lastHitTime || now()->diffInMinutes($lastHitTime) >= $interval) {
            $client = new Client();
            $response = $client->get($cronJob->url);

            // Handle response if needed
            $statusCode = $response->getStatusCode();
            // Additional logic...

            $this->info("URL {$cronJob->url} hit with status code: $statusCode");

            // Update last hit time in cache
            Cache::put('last_hit_time_' . $cronJob->id, now(), now()->addMinutes($interval));
        } else {
            $this->info("URL {$cronJob->url} skipped. Interval not elapsed yet.");
        }
    }
}
