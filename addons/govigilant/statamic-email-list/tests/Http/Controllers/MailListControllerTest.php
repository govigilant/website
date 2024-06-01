<?php

namespace Vigilant\EmailList\Tests\Http\Controllers;

use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Vigilant\EmailList\Events\EntrySubmitted;
use Vigilant\EmailList\Models\Entry;
use Vigilant\EmailList\Tests\TestCase;

class MailListControllerTest extends TestCase
{
    #[Test]
    public function it_creates_entry(): void
    {
        Event::fake();

        $this->post('!/statamic-email-list/submit', [
            'list' => 'waitlist',
            'email' => 'example@govigilant.io',
        ]);

        /** @var ?Entry $createdEntry */
        $createdEntry = Entry::query()->firstWhere('email', '=', 'example@govigilant.io');

        $this->assertNotNull($createdEntry);
        $this->assertEquals('waitlist', $createdEntry->list);

        Event::assertDispatched(EntrySubmitted::class);
    }

    #[Test]
    public function it_validates(): void
    {
        $response = $this->post('!/statamic-email-list/submit');

        $response->assertRedirect();

        $entries =  Entry::query()->get();

        $this->assertEmpty($entries);
    }
}
