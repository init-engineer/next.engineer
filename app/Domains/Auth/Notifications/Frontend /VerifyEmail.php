<?php

namespace App\Domains\Auth\Notifications\Frontend;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
    public function via(mixed $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Verify E-mail Address'))
            ->line(__('Please click the button below to verify your email address.'))
            ->action(__('Verify E-mail Address'), $this->verificationUrl($notifiable))
            ->line(__('If you did not create an account, no further action is required.'));
    }

    protected function verificationUrl(mixed $notifiable): string
    {
        return URL::temporarySignedRoute(
            'frontend.auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
