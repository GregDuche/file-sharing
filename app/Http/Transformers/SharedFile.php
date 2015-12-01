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
class SharedFile extends \App\Http\Transformers\Transformer {
  	
  	protected $mapping = [
  		'id'	=> 'id|integer',
  		'request_id'	=> 'request_id|integer',
		  'file_id' => 'file_id|integer'
  	];


  	/**
     * List all the includes name available
     * @var Array
     */
    protected $availableIncludes = [
    	'request', 'file'
    ];

    public function includeRequest($sharedFile) {
    	$request = $sharedFile->request;
    	if ($request) {
    		return $this->item($request, new \App\Http\Transformers\SharedFileRequest);	
    	}
    }

    public function includeFile($sharedFile) {
    	$file = $sharedFile->file;
    	if ($file) {
    		return $this->item($file, new \App\Http\Transformers\File);	
    	}
    }
}
