<?php

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('welcome notification is sent when registered event is fired', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Fire the Registered event to test our listener
    Event::dispatch(new Registered($user));

    // Assert that the welcome notification was sent
    Notification::assertSentTo($user, WelcomeNotification::class);
});

test('welcome notification has correct subject and template', function () {
    $user = User::factory()->create();
    
    $notification = new WelcomeNotification();
    $mailMessage = $notification->toMail($user);

    expect($mailMessage->subject)->toBe('Welcome to Delight!');
    expect($mailMessage->view)->toBe('emails.welcome');
});
