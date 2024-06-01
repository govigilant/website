<?php

namespace Vigilant\EmailList\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Vigilant\EmailList\Events\EntrySubmitted;
use Vigilant\EmailList\Http\Requests\SubmitRequest;
use Vigilant\EmailList\Models\Entry;

class MailListController extends Controller
{
    public function submit(SubmitRequest $request): RedirectResponse
    {
        /** @var Entry $entry */
        $entry = Entry::query()->updateOrCreate([
            'list' => $request->list,
            'email' => $request->email,
        ], [
            'data' => $request->data ?? [],
        ]);

        EntrySubmitted::dispatch($entry);

        if (! blank($request->return_url)) {
            return redirect()->to($request->return_url)->with([
                'email_submitted' => true,
            ]);
        }

        return redirect()->back()->with([
            'email_submitted' => true,
        ]);
    }

    public function confirm(Entry $entry): RedirectResponse
    {
        $entry->update([
            'confirmed' => true,
        ]);

        return redirect()->to(config('statamic-email-list.confirmation_url'));
    }
}
