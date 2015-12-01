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
class SharedFileRecipient extends \App\Http\Transformers\Transformer {
  	
  	protected $mapping = [
  		'id'	=> 'id|integer',
  		'user_id'	=> 'user_id|integer',
		'email' => 'email',
		'request_id' => 'request_id',
  	];

  	/**
     * List all the includes name available
     * @var Array
     */
    protected $availableIncludes = [
    	'request'
    ];

    public function includeRequest($sharedFileRecipient) {
    	$request = $sharedFileRecipient->request;
    	if ($request) {
    		return $this->item($request, new \App\Http\Transformers\SharedFileRequest);	
    	}
    }
};