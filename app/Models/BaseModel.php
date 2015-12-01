<?php
/**
* Base Model
* @package  App\Models
* @author  Gregoire Duché <greg.duche@mrkdevelopment.com>
* @copyright  M R K Development Pty Ltd.
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
namespace App\Models;

use Illuminate\Support\MessageBag;
use Input;

/**
 * BaseModel for the app
 */
class BaseModel extends \Eloquent
{
    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    public static $rules = array();

    /**
     * Flag to use the auto validation functionality.
     *
     * @var boolean
     */
    public static $autoValidate = true;

    public $validationErrors;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->validationErrors = new MessageBag;
    }

    /**
     * Filter argument variable which is used to set the filtering definitions.
     * TODO: provide an example here.
     * @var array
     */
    public static $filterArgs = array();

    /**
     * Query scope for adding searching based on $filterArgs
     *
     * @param [type] $query Query builder object
     *
     * @return [type] [description]
     */
    public static function scopeSearch($query, $input = null)
    {
        if ($input == null) {
            $input = Input::all();
        }

        // sort
        $sortField = isset($_GET['sort']) ? $_GET['sort'] : false;

        $sortField = static::isSortable($sortField);

        if ($sortField) {
            $direction = $input['direction'];
            $direction = ($direction == 'asc') ? 'asc' : 'desc';

            $query->orderBy($sortField, $direction);
        }

        $data = [];
        foreach (array_keys(static::$filterArgs) as $key) {
            if (isset($input[$key])) {
                $data[$key] = $input[$key];
            }
        }

        foreach ($data as $key => $value) {
            $filter = static::$filterArgs[$key];

            if ( is_null($value) || (strlen($value) == 0)) {
                continue;
            }

            $fieldName = isset($filter['field']) ? $filter['field'] : $key;

            switch ($filter['type']) {
                case 'value':
                    $query->where($fieldName, '=', $value);
                    break;

                case 'like':
                    $query->where($fieldName, 'LIKE', '%' . $value . '%');
                    break;

                case 'custom':
                    $query->where($fieldName, $filter['custom'], $value);
                    break;

                case 'gt':
                    $query->where($fieldName, '>', $value);
                    break;

                case 'lt':
                    $query->where($fieldName, '<', $value);
                    break;

                case 'scope':
                    // $query = static::{'scope' . $filter['method']}($query);
                    break;

                default:
                    break;
            }
        }

        return $query;
    }

    /**
     * Checks if field is sortable
     *
     * @param [type] $sortField [description]
     *
     * @return boolean [description]
     */
    public static function isSortable($sortField)
    {
        if (isset(static::$filterArgs[$sortField]['sortable']) && static::$filterArgs[$sortField]['sortable']) {
            return isset(static::$filterArgs[$sortField]['field']) ? static::$filterArgs[$sortField]['field'] : $sortField;
        }

        return false;
    }

    /**
     * Helper method to set the filters
     *
     * @param [type] $filters [description]
     *
     * @return [type] [description]
     */
    public static function setFilters($filters)
    {
        static::$filterArgs = $filters;
    }

    /**
     * Mysql Date to Carbon date converted
     *
     * @param [type] $dateStr Date in Y-m-d format
     *
     * @return [type] [description]
     */
    public static function mysqlDate($dateStr)
    {
        return \Carbon\Carbon::instance(new \Datetime($dateStr));
    }

    public static function boot()
    {
        parent::boot();

        $class = get_called_class();

        $class::saving(function ($item) use ($class) {

            if (isset($item->id)) {

                // skip auto validation
                if (!static::$autoValidate) {
                    return true;
                }

                // automatically setting the ID to avoid for unique validation.
                foreach ($class::$rules as $i => $rule) {
                    if (strpos($rule, "unique:") !== false) {
                        $fieldRules = explode('|', $rule);

                        foreach ($fieldRules as $j => $fieldRule) {
                            // echo "$fileRule -->" . strpos($fieldRule, 'unique:');
                            if (strpos($fieldRule, 'unique:') !== false) {
                                $uniqueRulesParts = explode(',', $fieldRule);
                                if (count($uniqueRulesParts) > 2) {
                                    $uniqueRulesParts[2] = $item->id;
                                } else {
                                    $uniqueRulesParts[] = $item->id;
                                }

                                $fieldRules[$j] = implode(',', $uniqueRulesParts);
                            }
                        }
                        $class::$rules[$i] = implode('|', $fieldRules);
                    }
                }
            }

            $validator = \Validator::make($item->attributes, $class::$rules);

            $success = $validator->passes();

            if ($success) {
                // if the model is valid, unset old errors
                if ($item->validationErrors->count() > 0) {
                    $item->validationErrors = new MessageBag;
                }
            } else {
                // otherwise set the new ones
                $item->validationErrors = $validator->messages();
            }

            try {
                $success = $item->savingHook($success);
            } catch (\Exception $e) {
                //do nothing
            }

            return $success;
        });
    }

    /**
     * Saving hook for providing addition code for saving hook
     *
     * @param [type] $responseStatus [description]
     *
     * @return [type] [description]
     */
    public function savingHook($responseStatus)
    {
        throw new \Exception("saving hook not implemented", 1);
    }
}
