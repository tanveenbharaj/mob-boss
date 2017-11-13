<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
class Admin extends Model
{
    // Import Notifiable Trait
    use Notifiable;

    protected $fillable=['name'];

    // Specify Slack Webhook URL to route notifications to
    public function routeNotificationForSlack() {
        return env('SLACK_WEBHOOK_URL');
    }
}