<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Closure;

class JapaneseVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    /**
     * @var Closure|null
     */
    public static $toMailCallback;

    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  (MustVerifyEmail&Model)  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        /** @var string $app_name */
        $app_name = config('app.name');
        return (new MailMessage)
                    ->from('noreply@laravel-bbs.com', $app_name)
                    ->subject('Laravel BBSのメール認証')
                    ->line('メールアドレスの検証を行うため下記のボタンをクリックしてください。')
                    ->action('メール認証', $verificationUrl)
                    ->line('もしアカウントを作成していない場合は追加の処理は必要ありません。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
    
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  (MustVerifyEmail&Model)  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable): string
    {
        /** @var int $auth_verification_expire */
        $auth_verification_expire = Config::get('auth.verification.expire', 60);
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($auth_verification_expire),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * @param  \Closure|null  $callback
     * @return void
     */
    public static function toMailUsing($callback): void
    {
        static::$toMailCallback = $callback;
    }
}
