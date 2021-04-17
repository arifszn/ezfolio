<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use Log;

class ResetApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the app by running seeds';

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
     * @return int
     */
    public function handle()
    {
        try {
            Artisan::call('db:seed --force');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
