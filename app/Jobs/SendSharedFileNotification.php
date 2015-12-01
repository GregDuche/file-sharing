<?php
/**
* Send Shared File Notification
* @package  App\Jobs
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mailgun\Mailgun;

/**
 * Sends a notification to the recipient of a request after a file sharing
 */
class SendSharedFileNotification extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $request;
    private $recipient;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $recipient)
    {
        $this->request = $request;
        $this->recipient = $recipient;    
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mg = new Mailgun(env('MAILGUN_API_KEY'));
        $domain = env('MAILGUN_DOMAIN');
        

        $mg->sendMessage($domain, 
                    array('from'    => 'noreply@mrkdevelopment.com', 
                          'to'      => $this->recipient->email, 
                          'subject' => 'Some files have been shared with you', 
                          'text'    => view('emails.sharing.notif-txt')->render(),
                          'html'    => view('emails.sharing.notif')->render()));

        
    }
}
