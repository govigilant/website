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

        return redirect()->back()->with([
            'submitted' => true,
        ]);
    }

    public function confirm(Entry $entry): RedirectResponse
    {
        $entry->update([
            'confirmed' => true,
        ]);

        return redirect()->to('/')->with('emailConfirmed', true);
    }
}
