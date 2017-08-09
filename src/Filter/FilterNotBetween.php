<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterNotBetween extends FilterBetween {

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereNotBetweenFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereNotBetween' : 'whereNotBetween';
        return $model->$whereNotBetweenFunc($tablePrefix.$this->column, [$this->valueA, $this->valueB]);
    }
}
