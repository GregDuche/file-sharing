<?php
/**
* FS Settings
* @package  App\Http\Controllers
* @author  Gregoire Duche <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Controllers;

class Share extends Controller {

	/**
	 * Displays the user administration
	 */
	public function index() {
		return view('share.index')->with('user', \Auth::user());
	}

	public function myFiles() {
		return view('share.my-files')->with('user', \Auth::user());
	}
}
?>