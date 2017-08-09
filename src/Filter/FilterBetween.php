<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterBetween extends BaseFilterBetween {

    /**
     * Constructor
     *
     * @var string  $column
     * @var mixed   $valueA
     * @var mixed   $valueB
     * @return void
     */
    public function __construct($column, $valueA, $valueB)
    {
        $this->column = $column;
        $this->valueA = $valueA;
        $this->valueB = $valueB;
    }
}
