<?php

namespace App\Console\Commands;

use App\Jobs\ReceiveSystemMail;
use Illuminate\Console\Command;

class EmailReceiver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receive_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Receiver';

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
        ReceiveSystemMail::dispatch();

    }

}
