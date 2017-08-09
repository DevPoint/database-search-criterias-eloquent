<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Sort;

class SortGroup extends AbstractSort {

    /**
     * @var array
     */
    protected $sorts;

    /**
     * Constructor
     *
     * @var array   $sorts
     * @return void
     */
    public function __construct($sorts)
    {
        $this->sorts = $sorts;
    }

   /**
     * Check must apply any joins
     *
     * @return boolean
     */
    public function hasJoins()
    {
        $result = false;
        foreach ($this->sorts as $sort)
        {
            if ($sort->hasJoins())
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
        foreach ($this->sorts as $sort)
        {
            if ($sort->hasJoins())
            {
                $query = $sort->applyJoins($query, $tableName);
            }
        }
        return $query;
    }

    /**
     * @param mixed   $model
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $tableName)
    {
        if (count($this->sorts))
        {
            $sortCount = count($this->sorts);
            $sort = $this->sorts[0];
            $query = $sort->apply($model, $tableName);
            for ($i = 1; $i < $sortCount; $i++)
            {
                $sort = $this->sorts[$i];
                $query = $sort->apply($query, $tableName);
            }
            return $query;
        }
        else
        {
            return $model;
        }
    }
}
