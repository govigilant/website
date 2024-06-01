<?php

namespace Vigilant\EmailList\Http\Controllers\Cp;

use Illuminate\Routing\Controller;
use Vigilant\EmailList\Models\Entry;

class MailListController extends Controller
{
    public function index(): mixed
    {
       return view('emaillist::cp.index', [
           'confirmed' => Entry::query()->where('confirmed', '=', true)->count(),
           'unconfirmed' => Entry::query()->where('confirmed', '=', false)->count(),
           'entries' => Entry::query()->paginate(25),
       ]);
    }
}
