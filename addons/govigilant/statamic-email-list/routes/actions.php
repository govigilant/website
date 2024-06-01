<?php

use Illuminate\Support\Facades\Route;
use Vigilant\EmailList\Http\Controllers\MailListController;

Route::post('submit', [MailListController::class, 'submit'])->name('email-list.submit');
