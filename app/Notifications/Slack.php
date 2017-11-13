<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
class Slack extends Notification
{
    use Queueable;
    private $task;
     /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->task = $task;
    }
     /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }
     /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {

     /*
        $task = $this->task;
        return (new SlackMessage)
            ->success()
            ->content("A task has been completed")
            ->attachment(function ($attachment) use ($task) {
                $attachment->title($task->title, route('task', $task->id))
                    ->content($task->description);
            });
        */
        return (new SlackMessage)
                ->success()
                ->content('vishal1234');
    }

}