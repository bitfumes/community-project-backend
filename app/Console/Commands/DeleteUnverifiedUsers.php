<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unverified-users {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users unverified for more than the given days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $days = $this->argument('days');

        if ($days <= 0) {
            $this->error('Invalid input');
        }

        User::olderThan($days, 'days')->isNotVerified()->delete();
    }
}
