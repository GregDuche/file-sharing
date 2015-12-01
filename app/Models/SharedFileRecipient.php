<?php
/**
* SharedFileRecipient
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Models;

/**
 * A single recipient of a file share request
 */
class SharedFileRecipient extends BaseModel
{  
	public $table = 'shared_file_recipients';

    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'user_id',
        'email',
    ];

    public static $rules = [
        'request_id' => 'required',
        'email' => 'required|email',
    ];

     public static $filterArgs = [
       'user_id' => ['type' => 'value', 'sortable' => 'true'],
    ];

    /**
     * Relationship
     *
     * @return [type] [description]
     */
    public function request()
    {
        return $this->belongsTo('\App\Models\SharedFileRequest', 'request_id');
    }

}
