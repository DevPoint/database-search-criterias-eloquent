<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

use Devpoint\Database\SearchCriterias\Contracts\SearchFilter as SearchFilterContract;
use Devpoint\Database\SearchCriterias\Contracts\SearchScope;

abstract class AbstractFilter implements SearchFilterContract {

    /**
     * @var SearchScope
     */
    protected $scope;
    
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
     * Check must apply any joins
     *
     * @param mixed   $model
     * @param string  $tableName (optional)
     * @return void
     */
    public function applyJoins($model, $tableName)
    {
        return $model;
    }

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    abstract public function apply($model, $boolean, $tableName);
}
