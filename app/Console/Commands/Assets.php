<?php

namespace App\Console\Commands;

use App\Models\SyncRecords;
use App\Providers\WebSocketProvider;
use Illuminate\Console\Command;

class Assets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'assets migration';

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
        $websoket = new WebSocketProvider();
        $websoket->start();
    }


}
