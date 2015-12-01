<?php
/**
* SharedFile
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Models;

/**
 * Relationship between request, file and user
 */
class SharedFile extends BaseModel
{
    public $table = 'shared_files';

    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'file_id',
        'user_id',
    ];

    public static $rules = [
        'request_id' => 'required',
        'file_id' => 'required',
        'user_id' => 'required',
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

    public function file()
    {
        return $this->belongsTo('\App\Models\File');
    }
}
