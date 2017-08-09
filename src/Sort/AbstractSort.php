<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Sort;

use Devpoint\Database\SearchCriterias\Contracts\SearchSort as SearchSortContract;

abstract class AbstractSort implements SearchSortContract {

    /**
     * Check must apply any joins
     *
     * @return boolean
     */
    public function hasJoins()
    {
        return false;
    }

    /**
     * Apply any joins
     *
     * @param mixed   $model
     * @param string  $tableName
     * @return void
     */
    public function applyJoins($model, $tableName)
    {
        return $model;
    }

    /**
     * @param mixed   $model
     * @param string  $tableName (optional)
     * @return mixed
     */
    abstract public function apply($model, $tableName);
}
