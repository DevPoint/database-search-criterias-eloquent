<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterExp extends BaseFilterExp {

    /**
     * Constructor
     *
     * @var string  $column
     * @var mixed   $operator
     * @var mixed   $value
     * @return void
     */
    public function __construct($column, $operator, $value)
    {
        $this->column = $column;
        $this->operator = strtolower($operator);
        $this->value = $value;
    }
}
