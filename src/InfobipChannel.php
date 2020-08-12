<?php

namespace NotificationChannels\Infobip;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\Events\NotificationFailed;
use NotificationChannels\Infobip\Events\NotificationSent;
use NotificationChannels\Infobip\Exceptions\CouldNotSendNotification;

class InfobipChannel
{
    /**
     * @var Dispatcher
     */
    public $events;

    /**
     * @var Infobip
     */
    public $infobip;

    /**
     * InfobipChannel constructor.
     *
     * @param Infobip $infobip
     * @param Dispatcher $events
     */
    public function __construct(Infobip $infobip, Dispatcher $events)
    {
        $this->events = $events;
        $this->infobip = $infobip;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Infobip\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $recipient = $this->getRecipient($notifiable);
        $message = $notification->toInfoBip($notifiable);

        try {
            $response = $this->infobip->sendMessage($message, $recipient);
            $sentMessageInfo = $response->getMessages()[0];

            $this->events->dispatch(new NotificationSent($notifiable, $notification, $sentMessageInfo));
        } catch (\Exception $exception) {
            //$this->events->dispatch(new NotificationFailed($notifiable, $notification, $exception));
            dd($exception);
        }
    }

    /**
     * Get message recipient.
     *
     * @param $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function getRecipient($notifiable)
    {
        if ($notifiable->routeNotificationForInfoBip()) {
            return $notifiable->routeNotificationForInfoBip();
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
