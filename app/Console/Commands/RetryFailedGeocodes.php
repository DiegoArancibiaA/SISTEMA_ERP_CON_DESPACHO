<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use Illuminate\Support\Facades\Log;

class RetryFailedGeocodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geocode:retry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry geocoding for failed attempts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
