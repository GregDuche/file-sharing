<?php 
/**
* Users repository
* @package  App\Repositories
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Repositories;

use Auth;
use Exception;
use Validator;

use App\Models\User;

class Users
{
    
    /**
     *
     *
     * @var Illuminate\Support\Validator
     */
    public $validator;

    /**
     * Create Resource
     *
     * @param array $input Input
     *
     * @return App\Models\User Resource instance
     */
    public function create($input)
    {
        $rules = [
                    'name'     => 'required|max:255',
                    'email'    => 'required|email|max:255|unique:users,email,NULL,id',
                    'password' => 'required|confirmed|min:6',
                ];

        $this->validator    = Validator::make($input, $rules);

        if ($this->validator->passes()) {
            $input['password'] = bcrypt($input['password']);
            unset($input['password_confirmation']);

            $resource = User::create($input);
            return $resource;
        } else {
            throw new Exception("Validation Errors:".json_encode($this->validator->getMessageBag()), 422);
        }
    }

    /**
     * Search users
     *
     * @param array $input Input params
     *
     * @return Illuminate\Database\Query
     */
    public function search($input)
    {
        $query = \App\Models\User::search($input);
        return $query;
    }

     /**
     * Get Resource
     *
     * @param integer $id Resource ID
     *
     * @return Viz\Models\User Resource instance
     */
    public function get($id)
    {
        $resource = \App\Models\User::find($id);

        if (!$resource) {
            throw new Exception("Resource Not Found", 404);
        }

        return $resource;
    }


    /**
     * Delete a resource
     *
     * @param integer $id Resource ID
     *
     * @return boolean Confirmation of delete
     */
    public function delete($id)
    {
        $resource = $this->get($id);
        if ($resource->delete()) {
            return true;
        }

        return false;
    }
}
