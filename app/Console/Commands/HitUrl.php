<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJob;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Hit all URLs from cron job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Fetching URLs from the database.");

        // Fetch all URLs from the database
        $cronJobs = CronJob::all();

        // Iterate over each URL and process them one by one
        foreach ($cronJobs as $cronJob) {
            $this->processUrl($cronJob);
        }

        $this->info("All URLs processed successfully.");
    }

    /**
     * Process a single URL.
     *
     * @param  \App\Models\CronJob  $cronJob
     * @return void
     */
    protected function processUrl(CronJob $cronJob)
    {
        $this->info("Processing URL: {$cronJob->url}");

        $interval = $cronJob->interval_in_minutes;
        $cacheKey = 'last_hit_time_' . $cronJob->id;

        // Get the last hit time from cache or default to null
        $lastHitTime = Cache::get($cacheKey);

        // Calculate the next hit time based on the interval
        $nextHitTime = $lastHitTime ? $lastHitTime->addMinutes($interval) : now();

        // Check if the interval has elapsed
        if ($nextHitTime->isPast()) {
            $client = new Client();
            $response = $client->get($cronJob->url);

            // Handle response if needed
            $statusCode = $response->getStatusCode();
            // Additional logic...

            // Log the URL hit with status code
            Log::info("URL {$cronJob->url} hit with status code: $statusCode");
            $this->info("URL {$cronJob->url} hit with status code: $statusCode");

            // Update last hit time in cache
            Cache::put($cacheKey, now(), now()->addMinutes($interval));
        } else {
            $this->info("URL {$cronJob->url} skipped. Interval not elapsed yet.");
        }
    }
}
