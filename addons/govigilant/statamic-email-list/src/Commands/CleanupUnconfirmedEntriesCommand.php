<?php

namespace Vigilant\EmailList\Commands;

use Illuminate\Console\Command;
use Vigilant\EmailList\Models\Entry;

class CleanupUnconfirmedEntriesCommand extends Command
{
    protected $signature = 'email-lists:cleanup';

    protected $description = 'Delete unconfirmed entries';

    public function handle(): int
    {
        Entry::query()
            ->where('confirmed', '=', false)
            ->where('created_at', '<', now()->subDays(3))
            ->delete();

        return static::SUCCESS;
    }
}
