<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

class FilterNull extends BaseFilterNull {

    /**
     * Constructor
     *
     * @var string  $column
     * @return void
     */
    public function __construct($column)
    {
        $this->column = $column;
    }
}
