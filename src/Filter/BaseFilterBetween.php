<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class BaseFilterBetween extends AbstractFilter {

    /**
     * @var string
     */
    protected $column;
    
    /**
     * @var mixed
     */
    protected $valueA;

    /**
     * @var mixed
     */
    protected $valueB;

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereBetweenFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereBetween' : 'whereBetween';
        return $model->$whereBetweenFunc($tablePrefix.$this->column, [$this->valueA, $this->valueB]);
    }
}
