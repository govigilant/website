<?php

namespace Vigilant\EmailList\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Vigilant\EmailList\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    use LazilyRefreshDatabase;

    protected string $addonServiceProvider = ServiceProvider::class;
}
