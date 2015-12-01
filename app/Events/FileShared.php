<?php
/**
* FileShared
* @package  App\Events
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

/**
 * Class FileShared
 * Event broadcast on file sharing
 */
class FileShared extends Event {

	use SerializesModels;

	private $request;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($request)
	{
		$this->request = $request;
	}
	
	/**
	 * getRequest accessor
	 * @return \App\Models\FileSharedRequest;
	 */
	public function getRequest() {
		return $this->request;
	}
}
