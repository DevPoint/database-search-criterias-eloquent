<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class BaseFilterNull extends AbstractFilter {

    /**
     * @var string
     */
    protected $column;
    
    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereNullFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereNull' : 'whereNull';
        return $model->$whereNullFunc($tablePrefix.$this->column);
    }
}
