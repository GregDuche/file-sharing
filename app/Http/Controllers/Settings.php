<?php
/**
* FS Settings
* @package  App\Http\Controllers
* @author  Gregoire Duche <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Controllers;

class Settings extends Controller {

	/**
	 * Displays the user administration
	 */
	public function users() {
		return view('admin.users');
	}
}
?>