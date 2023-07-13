<?php

namespace Biigle\Modules\abysses\Notifications;

use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobStateChanged extends Notification
{
    /**
     * The job which changed state.
     *
     * @var AbyssesJob
     */
    protected $job;

    /**
     * Create a new notification instance.
     *
     * @param AbyssesJob $job
     * @return void
     */
    public function __construct(AbyssesJob $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $settings = config('abysses.notifications.default_settings');

        if (config('abysses.notifications.allow_user_settings') === true) {
            $settings = $notifiable->getSettings('abysses_notifications', $settings);
        }

        if ($settings === 'web') {
            return ['database'];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject($this->getTitle($this->job))
            ->line($this->getMessage($this->job));

        if (config('app.url')) {
            $message = $message->action('Show job', route('abysses', $this->job->id));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $array = [
            'title' => $this->getTitle($this->job),
            'message' => $this->getMessage($this->job),
        ];

        if (config('app.url')) {
            $array['action'] = 'Show job';
            $array['actionLink'] = route('abysses', $this->job->id);
        }

        return $array;
    }

    /**
     * Get the title for the state change.
     *
     * @param AbyssesJob $job
     * @return string
     */
    protected function getTitle($job)
    {
        return 'Abysses job state changed';
    }

    /**
     * Get the message for the state change.
     *
     * @param AbyssesJob $job
     * @return string
     */
    protected function getMessage($job)
    {
        return 'The state of the Abysses job has changed';
    }
}
