<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterNotIn extends FilterIn {

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereNotInFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereNotIn' : 'whereNotIn';
        return $model->$whereNotInFunc($tablePrefix.$this->column, $this->values);
    }    
}
