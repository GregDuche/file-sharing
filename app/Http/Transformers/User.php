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
class User extends \App\Http\Transformers\Transformer {
  	protected $mapping = [
  		'id'	=> 'id|integer',
  		'name'	=> 'name',
		  'email' => 'email', 
      'super_admin' => 'super_admin|boolean',
  	];


  	/**
     * List all the includes name available
     * @var Array
     */
    protected $availableIncludes = [
    	'groups', 
      'clients'
    ];

    public function includeGroups($user) {
    	$groups = $user->groups;
      return $this->collection($groups, new \App\Http\Transformers\Group);
    }

    public function includeClients($user) {
      $clients = $user->clients;
      return $this->collection($clients, new \App\Http\Transformers\Client);
    }
}
