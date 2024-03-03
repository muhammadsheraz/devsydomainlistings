<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\DomainStatus;
use App\Models\Domain;

class UpdateDomainStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Domain Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Domain::where('status', DomainStatus::UPCOMING)
            ->where('starting_date', '<=', now())
            ->update(['status' => DomainStatus::ACTIVE]);
    }
}
