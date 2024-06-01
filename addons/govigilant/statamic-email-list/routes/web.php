<?php

use Illuminate\Support\Facades\Route;
use Vigilant\EmailList\Http\Controllers\MailListController;

Route::get('confirm/{entry}', [MailListController::class, 'confirm'])
    ->middleware('signed')
    ->name('email-list.confirm');
