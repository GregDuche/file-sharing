<?php
/**
* User
* @package  App\Models\User
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'users';

    public static $autoValidate = false;

    //use SoftDeletingTrait;

    public static $rules = [
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['name', 'email', 'password'];

    /**
     * Definitions of whats filterable and how
     *
     * @var array
     */
    public static $filterArgs = array(
        'id'            => ['type' => 'value', 'sortable' => true],
        'user_id'       => ['type' => 'value', 'sortable' => true, 'field' => 'users.id'],
        'name'          => ['type' => 'like', 'sortable' => true],
        'email'         => ['type' => 'value', 'sortable' => true],
        'super_admin'   => ['type' => 'value', 'sortable' => true],
    );

}
