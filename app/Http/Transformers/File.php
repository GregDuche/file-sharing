<?php 
/**
* User
* @package  App\Http\Transformers
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use App\ModelsFile;

class File extends \App\Http\Transformers\Transformer {
  	
  	protected $mapping = [
  		'id' => 'id|integer',
  		'status' => 'status',
  		'original_filename' => 'original_filename',
      'filename' => 'filename',
  		'path' => 'path',
  		'type' => 'type',
  		'created_at' => 'created_at|datetime',
  		'links' => 'links|json',
  	];


  	/**
     * List all the includes name available
     * @var Array
     */
    protected $availableIncludes = [
    	
    ];

    
}