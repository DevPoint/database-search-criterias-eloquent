<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class BaseFilterIn extends AbstractFilter {

    /**
     * @var string
     */
    protected $column;
    
    /**
     * @var array
     */
    protected $values;

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereInFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereIn' : 'whereIn';
        return $model->$whereInFunc($tablePrefix.$this->column, $this->values);
    }
}
