<?php

namespace Vigilant\EmailList\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vigilant\EmailList\Models\Entry;

class EntrySubmitted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Entry $entry,
    ) {
    }
}
