<?php
/**
* SharedFileRequest
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A file sharing request
 */
class SharedFileRequest extends BaseModel {
	
	public $table = 'shared_files_requests';

	use SoftDeletes;

	protected $fillable = [
		'user_id',
		'message',
		'expires_at',
		'status',
	];

	public static $rules = [
		'user_id'    => 'required',
		'message'    => 'required',
		'expires_at' => 'required',
	];

	public static $filterArgs = [
		'user_id' => ['type' => 'value', 'sortable' => 'true'],
		'from'    => ['type' => 'gt', 'field' => 'created_at'],
		'to'      => ['type' => 'lt', 'field' => 'created_at'],
	];

	/**
	 * Relationship
	 *
	 * @return [type] [description]
	 */
	public function files() {
		return $this->hasMany('\App\Models\SharedFile', 'request_id');
	}

	public function recipients() {
		return $this->hasMany('\App\Models\SharedFileRecipient', 'request_id');
	}


	public function user() {
		return $this->belongsTo('User', 'user_id');
	}

	public function getDates() {
		return array('created_at', 'updated_at', 'deleted_at', 'expires_at');
	}

	public function scopeExpired($query) {
		$query->where(function ($query) {
			$now = new \Datetime(Date('Y-m-d h:i:s a'), new \DateTimeZone('Australia/Sydney'));
			$query->where('expires_at', '<=', $now);
		});

		return $query;
	}

	public function scopeToClean($query) {
		return $query->where('status', '<>', 'cleaned');
	}

}
