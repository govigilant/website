<?php

namespace Vigilant\EmailList\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\PendingDispatch;
use Vigilant\EmailList\Jobs\SendConfirmationMailsJob;
use Vigilant\EmailList\Models\Entry;

class SendConfirmationMailsCommand extends Command
{
    protected $signature = 'email-lists:send-confirmation-mails';

    protected $description = 'Sent pending confirmation mails';

    public function handle(): int
    {
        Entry::query()
            ->where('mail_sent', '=', false)
            ->get()
            ->each(fn (Entry $entry): PendingDispatch => SendConfirmationMailsJob::dispatch($entry));

        return static::SUCCESS;
    }
}
