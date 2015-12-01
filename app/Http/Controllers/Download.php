<?php
/**
* FS Settings
* @package  App\Http\Controllers
* @author  Gregoire Duche <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Controllers;
use OpenCloud\Rackspace;

class Download extends Controller {

	/**
	 * Displays the user administration
	 */
	public function download($id) {

		$request = \App\Models\SharedFile::find($id);
		$now = \Carbon\Carbon::now();
		$expires = $request->request->expires_at;

		if ($now->timestamp < $expires->timestamp) {
			$file = $request->file;

			$path = $file->path;
			
			$rackRepo = new \App\Repositories\Rackspace;
	        $container = $rackRepo->getRackspaceContainer();

			$distantFile = $container->getObject($path);
			$url = $distantFile->getTemporaryUrl(60, 'GET');
			
			return \Redirect::away($url);	
		} else {
			return view('share.expired');
		}
		
	
	}
}
?>