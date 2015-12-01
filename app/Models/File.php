<?php
/**
* File
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Application File stored in DB
 */
class File extends BaseModel
{

    public $table = 'files';

    use SoftDeletes;

    public static $rules = [
        'user_id' => 'required',
    ];

    public $fillable = [
             'path',
             'user_id',
             'status',
             'size',
             'resolvable_type',
             'resolvable_id',
             'original_filename',
             'filename',
             'type',
             'links',
     ];

     /**
     * Filter arguments.
     *
     * @var array
     */
    public static $filterArgs = [
        'status'            => ['type' => 'like', 'sortable' => 'true'],
        'original_filename' => ['type' => 'like', 'sortable' => 'true'],
        'resolvable_type'   => ['type' => 'value', 'sortable' => 'true'],
        'resolvable_id'     => ['type' => 'value', 'sortable' => 'true'],
        'id'                => ['type' => 'value', 'sortable' => 'true'],
        'upload_id'         => ['type' => 'value', 'sortable' => 'true', 'field' => 'id'],
    ];
}
