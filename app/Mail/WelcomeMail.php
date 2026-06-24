<?php

namespace LaraCore\App\Mail;

use LaraCore\Framework\Mail\Mailable;

class WelcomeMail extends Mailable
{
    public function __construct(private array $user) {}

    public function build(): self
    {
        return $this
            ->subject('Welcome to ' . ($_ENV['APP_NAME'] ?? 'LaraCore'))
            ->view('mail.welcome', ['user' => $this->user]);
    }
}
