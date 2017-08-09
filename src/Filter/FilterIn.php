<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterIn extends BaseFilterIn {

    /**
     * Constructor
     *
     * @var string  $column
     * @var array   $values
     * @return void
     */
    public function __construct($column, $values)
    {
        $this->column = $column;
        $this->values = $values;
    }
}
