<?php
/**
* Rackspace repository
* @package  App\Repositories
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Repositories;

use OpenCloud\Rackspace as OpenCloudRackspace;
use OpenCloud\Common\Constants\Datetime;

/**
 * Utilities for Rackspace
 */
class Rackspace
{

	public function getClient() {
		$client = new OpenCloudRackspace(OpenCloudRackspace::US_IDENTITY_ENDPOINT, array(
          'username' => env('RACKSPACE_USERNAME'),
          'apiKey'   => env('RACKSPACE_API_KEY'),
        ));

        return $client;
	}

	public function getRackspaceContainer() {
        
        $client = $this->getClient();

        $service = $client->objectStoreService(null, 'HKG');
        $container = $service->getContainer(env('RACKSPACE_FS_CONTAINER'));

        return $container;
    }

    public function getQueue() {
        
        $client = $this->getClient();

        $service = $client->queuesService(null, 'HKG');
        
        $queue = $service->getQueue(env('RACKSPACE_QUEUE'));

        return $queue;
    }
}