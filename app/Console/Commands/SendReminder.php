<?php
namespace App\Console\Commands;

use App\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out reminders';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //reminder command
        //check of het tijd is om reminder te versturen
        //
        //refresh om de 60 seconden
       print_r("Reminder Daemon Started \n");
        while (true) {
            $account_sid = getenv('TWILIO_ACCOUNT_SID');
            $account_token = getenv("TWILIO_AUTH_TOKEN");
            $sending_number = getenv("TWILIO_PHONE_NUMBER");
            $twilio_client = new Client($account_sid, $account_token);


            $now = strtotime(date("Y-m-d H:i:s"));
            $ReminderTime = $now + 10800;

            $reminders = Reminder::where([['timezoneoffset', '<=', date("Y-m-d H:i:s", $ReminderTime)], ['status', 'pending'],['reply', 'accept']])->get();


            foreach ($reminders as $reminder) {
                $twilio_client->messages->create($reminder->mobile_no,
                    array("from" => $sending_number, "body" => "Reminder for: $reminder->message"));
                $reminder->status = 'sent';
                $reminder->save();
            }
            \sleep(60);
        }
    }
}
