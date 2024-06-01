<?php

namespace Vigilant\EmailList\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Vigilant\EmailList\Mail\ConfirmationMail;
use Vigilant\EmailList\Models\Entry;

class SendConfirmationMailsJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(protected Entry $entry)
    {
    }

    public function handle(): void
    {
        $this->entry->update([
            'mail_sent' => true,
        ]);

        Mail::to($this->entry->email)->send(new ConfirmationMail($this->entry));
    }

    public function uniqueId(): int
    {
       return $this->entry->id;
    }
}
