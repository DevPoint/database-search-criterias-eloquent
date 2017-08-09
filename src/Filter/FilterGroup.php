<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterGroup extends AbstractFilter {

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var string
     */
    protected $boolean;
    
    /**
     * Constructor
     *
     * @var array   $filters
     * @var string  $boolean - and|or|{empty}
     * @return void
     */
    public function __construct($filters, $boolean)
    {
        $this->filters = $filters;
        $this->boolean = $boolean;
    }

   /**
     * Check must apply any joins
     *
     * @return boolean
     */
    public function hasJoins()
    {
        $result = false;
        foreach ($this->filters as $filter)
        {
            if ($filter->hasJoins())
            {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * Apply all joins
     *
     * @param mixed   $model
     * @param string  $tableName (optional)
     * @return void
     */
    public function applyJoins($model, $tableName)
    {
        $query = $model;
        foreach ($this->filters as $filter)
        {
            if ($filter->hasJoins())
            {
                $query = $filter->applyJoins($query, $tableName);
            }
        }
        return $query;
    }

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        if (count($this->filters))
        {
            $whereFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhere' : 'where';
            return $model->$whereFunc(function($query) use ($tableName) 
            {
                $filterCount = count($this->filters);
                $filter = $this->filters[0];
                $query = $filter->apply($query, '', $tableName);
                for ($i = 1; $i < $filterCount; $i++)
                {
                    $filter = $this->filters[$i];
                    $query = $filter->apply($query, $this->boolean, $tableName);
                }
                return $query;
            });
        }
        else
        {
            return $model;
        }
    }
}
