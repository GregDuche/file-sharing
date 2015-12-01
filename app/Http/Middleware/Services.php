<?php 
/**
* Services
* @package  App\Http\Middleware
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Middleware;

use Closure;

class Services {

	/**
	 * Handle an incoming request and check that the user token has been given in the Request's headers
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		
		// Checking the X-Auth-Token parameter from the header to see if the user is authenticated
		if (\App\Services\UserToken::checkAndAuthToken($request)) {
			return $next($request);	
      	} else {
      		
      		//Authentication failed for some reason
      		$service = new \App\Http\Controllers\WebServices([]);
      		return $service->respondForbidden();
      	}
		
	}

}
