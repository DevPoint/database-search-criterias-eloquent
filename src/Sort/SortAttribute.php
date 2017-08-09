<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Sort;

class SortAttribute extends AbstractSort {

    /**
     * @var string
     */
    protected $column;
    
    /**
     * @var string
     */
    protected $order;

    /**
     * Constructor
     *
     * @var string  $column
     * @var string  $order
     * @return void
     */
    public function __construct($column, $order)
    {
        $this->column = $column;
        $this->order =  $order;
    }

    /**
     * @param mixed   $model
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        return $model->orderBy($tablePrefix.$this->column, $this->order);
    }
}
