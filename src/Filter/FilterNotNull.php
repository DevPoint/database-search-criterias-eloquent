<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterNotNull extends FilterNull {

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereNullFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereNotNull' : 'whereNotNull';
        return $model->$whereNullFunc($tablePrefix.$this->column);
    }
}
