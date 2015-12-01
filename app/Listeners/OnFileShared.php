<?php
/**
* Base Model
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Listeners;

use App\Events\FileShared;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

/**
 * Actions to be triggered after a file is shared
 */
class OnFileShared {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}


	public function handle(FileShared $event) {
		//Alright, we've recorded everything
		//and now, we have to upload the file and send notifications
		$request = $event->getRequest();
		$request->status = 'PENDING';
		$request->save();
                
                foreach ($request->files as $sharedFile) {
                	$file = $sharedFile->file;
                
                        \Queue::push(new \App\Jobs\MoveUploadedFile($file));
                	\Queue::push(new \App\Jobs\UpdateRequestStatus($request));
                }

                foreach ($request->recipients as $recipient) {
                	$user = \App\Models\User::where('email', '=', $recipient->email)->first();
                	if ($user) {
                		$recipient->user_id = $user->id;
                		$recipient->save();
                        }
                	
                        \Queue::push(new \App\Jobs\SendSharedFileNotification($request, $recipient));
                	
                	
                }
	}
}