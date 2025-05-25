<?php

namespace App\Console\Commands;

use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class PersistVisitsToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:persist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Persist visit timestamps from Redis to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::today()->format('Y-m-d');
        $key = 'visits:' . $date;
        $visitTimes = Redis::lrange($key, 0, -1);
        if (empty($visitTimes)) {
            return;
        }
        $visitsData = array_map(fn($timestamp) => ['visited_at' => $timestamp], $visitTimes);
        Visitor::insert($visitsData);
        Redis::del($key);
        \Log::info('Visits persist command executed  successfully.');

    }
}
