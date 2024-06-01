<?php

use Illuminate\Support\Facades\Route;
use Vigilant\EmailList\Http\Controllers\Cp\MailListController;

Route::middleware('statamic.cp.authenticated')
    ->prefix('email-list')
    ->group(function (): void {
        Route::get('', [MailListController::class, 'index'])->name('email-list.index');
    });
