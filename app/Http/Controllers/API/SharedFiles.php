<?php
/**
 * Users API
 */
namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use Input;

use App\Http\Transformers\SharedFile as SharedFilesTransformers;
use App\Repositories\SharedFiles as SharedFilesRepository;

/**
 * Users API
 */
class SharedFiles extends API
{

    protected $repository;

    /**
     * Constructor
     *
     * @param Request $request Laravel Request
     */
    public function __construct(Request $request)
    {
        $this->transformer = new SharedFilesTransformers;
        $this->repository  = new SharedFilesRepository;
        parent::__construct($request);
    }

    /**
     * Get all the tokens - not allowed without parameters,
     *
     * @return Response Collection of Resources
     */
    public function index($input = null)
    {

        // Input is being passed as a parameter so that we can mock it easily for the tests
        // If it is not a test, we'll use the facade:
        if (!$input) {
            $input = Input::all();
        }

        $data       = $this->repository->search($input);

        $records    = $this->getRecords($input);
        $data       = $data->paginate($records);

        $collection = $this->getResourceCollectionWithPagination($data, $this->transformer);

        return $this->respondOK($collection);
    }

    /**
     * Get Resource
     *
     * @param integer $id Resource ID
     *
     * @return Response Resource details
     */
    public function show($id)
    {
        try {
            $resource = $this->repository->get($id);
        } catch (Exception $e) {
            return $this->respondNotFound($e->getMessage());
        }

        if ($resource->super_admin) {
            return $this->respondNotFound('Cannot view the details');
        }

        $item = $this->getResourceItem($resource, $this->transformer);

        return $this->respondOK($item);
    }

    /**
     * Store a newly created resource in storage.
     * @param array $input Input data
     *
     * @return Response
     */
    public function store($input = null)
    {
        if (!$input) {
            $input = Input::all();
        }

        try {
            $resource = $this->repository->create($input);
        } catch (Exception $e) {
            if ($e->getCode() == 422) {
                return $this->respondValidationErrors($this->repository->validator->errors());
            }

            return $this->respondNotFound($e->getMessage());
        }

        return $this->respondCreated(['id' => $resource->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int   $id
     * @param array $input Input data
     *
     * @return Response
     */
    public function update($id, $input = null)
    {
        if (!$input) {
            $input = Input::all();
        }

        try {
            $resource = $this->repository->update($id, $input);
        } catch (Exception $e) {
            if ($e->getCode() == 422) {
                return $this->respondValidationErrors($this->repository->validator->errors());
            }

            return $this->respondNotFound($e->getMessage());
        }

        return $this->respondCreated(['id' => $resource->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id Resource ID
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $resource = $this->repository->get($id);
        } catch (Exception $e) {
            return $this->respondNotFound($e->getMessage());
        }

        if ($resource->super_admin) {
            return $this->respondNotFound('Cannot delete this user');
        }

        try {
            $this->repository->delete($id);
        } catch (Exception $e) {
            return $this->respondNotFound($e->getMessage());
        }

        return $this->setStatusCode(200)->respond(['deleted' => 'ok']);
    }

    /**
     * Return all resources
     *
     * @return array $input Input data
     */
    public function all($input = null)
    {
        if (!$input) {
            $input = Input::all();
        }

        $resources  = $this->repository->all($input);
        $collection = $this->getResourceCollection($resources, $this->transformer);

        return $this->respondOK($collection);
    }
}
