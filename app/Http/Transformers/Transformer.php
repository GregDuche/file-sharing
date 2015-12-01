<?php 
/**
* Transformer
* @package  App\Http\Transformer
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Http\Transformers;

use League\Fractal;

class Transformer extends Fractal\TransformerAbstract
{	
	/**
	 * Mapping to format the model's fields
	 * @var array attribute name => attribute | type
	 */
	protected $mapping = ['id' => 'id|integer'];

    /**
     * List all the includes name available
     * @var Array
     */
    protected $availableIncludes = [];

    public function transform(\App\Models\BaseModel $model)
    {
    	$formattedFields = [];
    	foreach ($this->mapping as $attributeName => $propertiesString) {
    		$properties = explode('|', $propertiesString);
    		$value = $model->$properties[0];
    		
    		if (isset($properties[1])) {
    			switch ($properties[1]) {
    				case 'boolean' : 
    				$value = (boolean) $value;
    				break;
    				case 'integer' : 
    				$value = (int) $value;
    				break;
    				case 'date' :
    				if ($value instanceof \Carbon\Carbon) {
    					$value = $value->toFormattedDateString();
    				} 
    				break;
    				case 'json' :
    				$value = json_decode($value);
    				break;
    			}
    		}
    		$formattedFields[$attributeName] = $value;
    	}
    	
    	return $formattedFields;
    }
}
