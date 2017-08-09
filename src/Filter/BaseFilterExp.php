<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class BaseFilterExp extends AbstractFilter {

    /**
     * @var string
     */
    protected $column;
    
    /**
     * @var string
     */
    protected $operator;
    
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $whereFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhere' : 'where';
        return $model->$whereFunc($tablePrefix.$this->column, $this->operator, $this->value);
    }
}
