<?php
/**
* API
* @package  App\Http\Controllers\API
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

/**
* API
* Defines common method for any services
*/
class API extends \App\Http\Controllers\WebServices
{

    /**
     * API name fro service return
     * @var string
     */
    protected $name      = 'FS Api';

    /**
     * Api prefix used for service return and for route calculation
     * @var string
     */
    protected $apiPrefix = 'api/';

    /**
     * Generic transformer for the service, used to format the results
     * @var \App\Http\Transformers\Transformer
     */
    protected $transformer;

    /**
     * Default number of items in the page
     * @var integer
     */
    protected $itemsPerPage   = 10;

    /**
     * Instantiate the service with API version and URL
     * @param Request $request self-injected Request object
     */
    public function __construct(Request $request)
    {
        $routeResolver = $request->getRouteResolver();

        // Populating the service data's header with service route, version and URL
        if ($route = $routeResolver()) {
            $action = $route->getAction();

            $prefix  = $action['prefix'];
            $version = substr($prefix, strlen($this->apiPrefix) + 1, strlen($prefix));
            $service = array('name' => $this->name, 'version' => $version, 'url' => \URL::to($route->getUri()));
        } else {
            // Default definition when calling the script from CLI
            $service = array('name' => $this->name, 'version' => 'undefined', 'url' => 'undefined');
        }

        parent::__construct($service);
    }

    /**
     * Set the pagination limit
     *
     * @param int $count the number of items per page
     */
    public function setItemsPerPage($count)
    {
        $this->itemsPerPage = $count;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($input = null)
    {
        // Implemented in subclasses
    }

    /**
     * Display the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function show($id)
    {
        // Implemented in subclasses
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function edit($id, $input = null)
    {
        // Implemented in subclasses
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function update($id)
    {
        // Implemented in subclasses
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        // Implemented in subclasses
    }
}
