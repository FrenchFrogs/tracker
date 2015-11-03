<?php namespace FrenchFrogs\Filterer;


/**
 * Class model used to apply filter to values
 *
 * Use polymorphisme with Trait \FrenchFrogs\Core\Filterer
 *
 * Class Filterer
 * @package FrenchFrogs\Filterer
 */
class Filterer
{

    /**
     * Main container
     *
     * @var array
     */
    protected $filters = [];


    /**
     * Constructor
     *
     * @param ...$params
     */
    public function __construct(...$params)
    {

        // if method "init" exist, we call it.
        if (method_exists($this, 'init')) {
            call_user_func_array([$this, 'init'], $params);
        }
    }

    /**
     * Set all filter as an array
     *
     * @param array $filters
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Add a single filter to the filters container
     *
     * @param $index
     * @param null $method
     * @param ...$params
     * @return $this
     */
    public function addFilter($index, $method = null, ...$params)
    {
        $this->filters[$index] = [$method, $params];
        return $this;
    }

    /**
     *
     * Remove a single filter from de filter container
     *
     * @param $index
     * @return $this
     */
    public function removeFilter($index)
    {
        if ($this->hasFilter($index)) {
            unset($this->filters[$index]);
        }

        return $this;
    }

    /**
     * Clear all filters from the filters container
     *
     *
     * @return $this
     */
    public function clearFilters()
    {
        $this->filters = [];

        return $this;
    }

    /**
     * Check if the filter exist in the filters container from the filter index
     *
     * @param $index
     * @return bool
     */
    public function hasFilter($index)
    {
        return isset($this->filters[$index]);
    }

    /**
     * Return the filter from the filters container from the filter container
     *
     * @param $index
     * @return mixed
     * @throws \Exception
     */
    public function getFilter($index)
    {
        if (!$this->hasFilter($index)) {
            throw new \Exception('Impossible de trouver le filtre : ' . $index);
        }

        return $this->filters[$index];
    }


    /**
     * Return array of all filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }


    /**
     * Filter de value
     *
     * Main method of the class
     *
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function filter($value)
    {
        foreach($this->getFilters() as $index => $filter) {
            // Extract the params
            list($method, $params) = $filter;

            // If method is null, we use the index as the method name
            $method = is_null($method) ? $index : $method;

            // Build the params for the filter
            array_unshift($params, $value);

            // If it's a anonymous function
            if (is_callable($method)) {

                $value = call_user_func_array($method, $params);

                // if it's a local method
            } else {

                if (!method_exists($this, $method)) {
                    throw new \Exception('Method "'. $method .'" not found for filter : ' . $index);
                }

                $value = call_user_func_array([$this, $method], $params);
            }
        }

        return $value;
    }



    /**
     *******************************
     *
     * Built in filter
     *
     *
     * *****************************
     */


    /**
     * return $value in lower case
     *
     * @param $value
     * @return string
     */
    public function lower($value)
    {
        return strtolower($value);
    }


    /**
     * Format a date
     *
     * @param $value
     * @param string $format
     * @return string
     */
    public function dateFormat($value, $format = 'd/m/Y')
    {
        return \Carbon\Carbon::parse($value)->format($format);
    }
}