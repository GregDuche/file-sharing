<?php
/**
 * Users API
 */
namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Input;

use App\Http\Transformers\SharedFile as FileTransformer;

/**
 * Users API
 */
class File extends API
{

 
    /**
     * Constructor
     *
     * @param Request $request Laravel Request
     */
    public function __construct(Request $request)
    {
        $this->transformer = new FileTransformer;
        
        parent::__construct($request);
    }

    public function upload() {
        $repository = new \App\Repositories\Files;

        try {
            $input                    = Input::all();
            $input['user_id']         = \Auth::user()->id;
            $response                 = $repository->handle($input);
        } catch (Exception $e) {
            return \Response::make($e->getMessage(), $e->getCode());
        }

        return $this->setStatusCode(200)->respond($response);
    }

    
}
