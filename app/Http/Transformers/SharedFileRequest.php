<?php 
/**
* User
* @package  App\Http\Transformers
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Transformers;

/**
* Video transformer
* Extends the Transformer to format a video file
*/
class SharedFileRequest extends \App\Http\Transformers\Transformer {
  	
  	protected $mapping = [
  		'id'	=> 'id|integer',
  		'created'	=> 'created_at|datetime',
  		'expires' => 'expires|datetime',
      'message' => 'message',
  		'uuid' => 'uuid',
      'status' => 'status'
  	];

  	/**
     * List all the includes name available
     * @var Array
     */
    protected $availableIncludes = [
    	'recipients', 'sharedFiles'
    ];

    public function includeSharedFiles($request) {
      $files = $request->files;
      if ($files) {
        return $this->collection($files, new \App\Http\Transformers\SharedFile);
      }
    }
    public function includeRecipients($request) {
    	$recipients = $request->recipients;
    	if ($recipients) {
    		return $this->collection($recipients, new \App\Http\Transformers\SharedFileRecipient);
    	}
    }
}
