<?php

namespace Vigilant\EmailList;

use Illuminate\Console\Scheduling\Schedule;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use Vigilant\EmailList\Commands\CleanupUnconfirmedEntriesCommand;
use Vigilant\EmailList\Commands\SendConfirmationMailsCommand;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
        'web' => __DIR__.'/../routes/web.php',
        'actions' => __DIR__.'/../routes/actions.php',
    ];

    /** @phpstan-ignore-next-line */
    protected $vite = [
        'input' => [
            'resources/css/cp.css',
        ],
        'publicDirectory' => 'resources/dist',
    ];

    protected $commands = [
        SendConfirmationMailsCommand::class,
        CleanupUnconfirmedEntriesCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(SendConfirmationMailsCommand::class)->everyMinute();
        $schedule->command(CleanupUnconfirmedEntriesCommand::class)->daily();
    }

    public function bootAddon(): void
    {
        $this
            ->bootMigrations()
            ->bootViews()
            ->bootCpNavigation();
    }

    protected function bootMigrations(): static
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        return $this;
    }

    protected function bootViews(): static
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'emaillist');

        return $this;
    }

    protected function bootCpNavigation(): static
    {
        Nav::extend(function ($nav) {
            $nav->content('E-mail Lists')
                ->route('email-list.index')
                ->icon('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>');
        });

        return $this;
    }
}
