<?php

namespace Devpoint\Database\SearchCriterias\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Devpoint\Database\SearchCriterias\Contracts\SearchFilter;
use Devpoint\Database\SearchCriterias\Contracts\SearchSort;
use Devpoint\Database\SearchCriterias\Core\AbstractSearchCriterias;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterExp;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterBetween;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterGroup;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterIn;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterNull;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterNotIn;
use Devpoint\Database\SearchCriterias\Eloquent\Filter\FilterNotNull;
use Devpoint\Database\SearchCriterias\Eloquent\Sort\SortAttribute;
use Devpoint\Database\SearchCriterias\Eloquent\Sort\SortGroup;

class SearchCriterias extends AbstractSearchCriterias {

    /**
     * @var array - with SearchFilter
     */
    private $filters;

    /**
     * @var array - with SearchSort
     */
    private $sorts;

    /**
     * @var array
     */
    private $withAttributes;

    /**
     * @var bool
     */
    private $withTrashed;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $count;

    /**
     * @var Model
     */
    private $model;

    /**
     * Constructor
     *
     * @param  Model  $model
     * @return void
     */
    public function __construct(Model $model = null)
    {
        $this->scope = self::newSearchScope();
        $this->filters = [];
        $this->sorts = [];
        $this->withAttributes = [];
        $this->withTrashed = false;
        $this->offset = 0;
        $this->count = -1;
        $this->model = $model;
    }

    /**
     * Convert attribute key to column
     *
     * @param  string  $attributeKey
     * @return string
     */ 
    protected function resolveAttributeKey($attributeKey)
    {
        $column = $attributeKey;
        return $column;
    }

    /**
     * Convert primary key to column
     *
     * @return string
     */ 
    protected function resolvePrimaryKey()
    {
        return $this->model->getKeyName();
    }

    /**
     * @param  string  $column
     * @param  mixed   $operator
     * @param  mixed   $value
     * @return SearchFilter
     */ 
    protected function _createFilterExp($column, $operator, $value)
    {
        return new FilterExp($column, $operator, $value);
    }
    
    /**
     * @param  string  $column
     * @param  mixed   $valueA
     * @param  mixed   $valueB
     * @return SearchFilter
     */ 
    protected function _createFilterBetween($column, $valueA, $valueB)
    {
        return new FilterBetween($column, $valueA, $valueB);
    }

    /**
     * @param  string  $column
     * @param  array   $values
     * @return SearchFilter
     */ 
    protected function _createFilterIn($column, $values)
    {
        return new FilterIn($column, $values);
    }

    /**
     * @param  string  $column
     * @param  array   $values
     * @return SearchFilter
     */ 
    protected function _createFilterNotIn($column, $values)
    {
        return new FilterNotIn($column, $values);
    }

    /**
     * @param  string  $column
     * @return SearchFilter
     */ 
    protected function _createFilterNull($column)
    {
        return new FilterNull($column);
    }

    /**
     * @param  string  $column
     * @return SearchFilter
     */ 
    protected function _createFilterNotNull($column)
    {
        return new FilterNotNull($column);
    }

    /**
     * @param  array   $filters
     * @param  string  $boolean
     * @return SearchFilter
     */ 
    protected function _createFilterGroup($filters, $boolean)
    {
        return new FilterGroup($filters, $boolean);
    }

    /**
     * @param  string  $attributeKey
     * @param  mixed   $operator
     * @param  mixed   $value
     * @return SearchFilter
     */ 
    public function createFilterExp($attributeKey, $operator, $value = null)
    {
        if (func_num_args() < 3)
        {
            $value = $operator;
            $operator = '=';
        }
        return $this->_createFilterExp($this->resolveAttributeKey($attributeKey), $operator, $value);
    }

    /**
     * @param  string  $attributeKey
     * @param  mixed   $valueA
     * @param  mixed   $valueB
     * @return SearchFilter
     */ 
    public function createFilterBetween($attributeKey, $valueA, $valueB)
    {
        return $this->_createFilterBetween($this->resolveAttributeKey($attributeKey), $valueA, $valueB);
    }

    /**
     * @param  string  $attributeKey
     * @return SearchFilter
     */ 
    public function createFilterNull($attributeKey)
    {
        return $this->_createFilterNull($this->resolveAttributeKey($attributeKey));
    }

    /**
     * @param  string  $attributeKey
     * @return SearchFilter
     */ 
    public function createFilterNotNull($attributeKey)
    {
        return $this->_createFilterNotNull($this->resolveAttributeKey($attributeKey));
    }

    /**
     * @param  string  $attributeKey
     * @param  array   $values
     * @return SearchFilter
     */ 
    public function createFilterIn($attributeKey, $values)
    {
        return $this->_createFilterIn($this->resolveAttributeKey($attributeKey), $values);
    }

    /**
     * @param  string  $attributeKey
     * @param  array   $values
     * @return SearchFilter
     */ 
    public function createFilterNotIn($attributeKey, $values)
    {
        return $this->_createFilterNotIn($this->resolveAttributeKey($attributeKey), $values);
    }

    /**
     * @param  array   $filters
     * @param  string  $boolean
     * @return SearchFilter
     */ 
    public function createFilterGroup($filters, $boolean = 'and')
    {
        return $this->_createFilterGroup($filters, $boolean);
    }

    /**
     * @param  mixed   $primaryId
     * @return SearchFilter
     */ 
    public function createFilterId($primaryId)
    {
        return $this->_createFilterExp($this->resolvePrimaryKey(), '=', $primaryId);
    }

    /**
     * @param  array   $primaryIds
     * @return SearchFilter
     */ 
    public function createFilterIds($primaryIds)
    {
        return $this->_createFilterIn($this->resolvePrimaryKey(), $primaryIds);
    }

    /**
     * @param  SearchFilter  $filter
     * @return self
     */ 
    public function filter(SearchFilter $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * @param  string  $attributeKey
     * @param  string  $order
     * @return SearchSort
     */ 
    protected function _createSortBy($attributeKey, $order)
    {
        return new SortAttribute($attributeKey, $order);
    }
    
    /**
     * @param  array   $sorts
     * @return SearchSort
     */ 
    protected function _createSortGroup($sorts)
    {
        return new SortGroup($sorts);
    }
    
    /**
     * @param  string  $attributeKey
     * @param  string|null  $order
     * @return SearchSort
     */ 
    public function createSortBy($attributeKey, $order = null)
    {
        if (func_num_args() < 2)
        {
            $order = 'asc';
        }
        return $this->_createSortBy($this->resolveAttributeKey($attributeKey), $order);
    }
    
    /**
     * @param  SearchSort  $sort
     * @return self
     */ 
    public function sort(SearchSort $sort)
    {
        $this->sorts[] = $sort;
        return $this;
    }

     /**
     * @param  string  $attributeKey
     * @return self
     */ 
    public function with($attributeKey)
    {
        $this->withAttributes[] = $attributeKey;
        return $this;
    }
   
    /**
     * @param  array   $attributeKeys
     * @return self
     */ 
    public function withGroup($attributeKeys)
    {
        foreach ($attributeKeys as $attributeKey) 
        {
            $this->withAttributes[] = $attributeKey;
        }
        return $this;
    }

    /**
     * @param  int   $offset
     * @param  int|null   $count
     * @return self
     */ 
    public function limit($offset, $count = null)
    {
        if (func_num_args() < 2)
        {
            $this->count = $offset;
            $this->offset = 0;
        }
        else
        {
            $this->offset = $offset;
            $this->count = $count;
        }
        return $this;
    }

    /**
     * @return self
     */ 
    public function withTrashed()
    {
        $this->withTrashed = true;
        return $this;
    }

    /**
     * Get the Eloquent Model
     *
     * @return Model
     */ 
    protected function _model()
    {
        return $this->model;
    }

    /**
     * Get Database Table name
     *
     * @return string
     */
    protected function _table()
    {
        return $this->_model()->getTable();
    }

    /**
     * Get Attribute Key for primary id
     *
     * @return string
     */
    protected function _qualifiedKeyName()
    {
        return $this->_model()->getQualifiedKeyName();
    }

   /**
     * Check must apply any joins
     *
     * @return boolean
     */
    protected function _hasJoins()
    {
        // check every filter
        $result = false;
        foreach ($this->filters as $filter)
        {
            if ($filter->hasJoins())
            {
                $result = true;
                break;
            }
        }

        // check every sorting
        if (!$result)
        {
            foreach ($this->sorts as $sort)
            {
                if ($sort->hasJoins())
                {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Apply all joins
     *
     * @param mixed   $model
     * @param string  $tableName (optional)
     * @return void
     */
    protected function _applyJoins($model, $tableName)
    {
        if (!empty($this->filters) || !empty($this->sorts))
        {
            $query = $model;

            // apply joins for every filter
            foreach ($this->filters as $filter)
            {
                if ($filter->hasJoins())
                {
                    $query = $filter->applyJoins($query, $tableName);
                }
            }

            // apply joins for every sorting
            foreach ($this->sorts as $sort)
            {
                if ($sort->hasJoins())
                {
                    $query = $sort->applyJoins($query, $tableName);
                }
            }

            return $query;
        }
        else
        {
            return $model;
        }
    }

    /**
     * Apply all filters
     *
     * @param  mixed   $model
     * @param  string  $tableName (optional)
     * @return mixed
     */
    protected function _applyFilters($model, $tableName)
    {
        if (!empty($this->filters))
        {
            $query = $model;
            foreach ($this->filters as $filter)
            {
                $query = $filter->apply($query, 'and', $tableName);
            }
            return $query;
        }
        else
        {
            return $model;
        }
    }

    /**
     * Apply all sortings
     *
     * @param  mixed   $model
     * @param  string  $tableName (optional)
     * @return mixed
     */
    protected function _applySortings($model, $tableName)
    {
        if (!empty($this->sorts))
        {
            $query = $model;
            foreach ($this->sorts as $sort)
            {
                $query = $sort->apply($query, $tableName);
            }
            return $query;
        }
        else
        {
            return $model;
        }
    }

    /**
     * Apply all with-attributes
     *
     * @param  mixed   $model
     * @param  array   $defaultAttributes (optional)
     * @return mixed
     */
    protected function _applyWithAttributes($model, $defaultAttributes = [])    
    {
        if (!empty($this->withAttributes) || !empty($defaultAttributes))
        {
            $withAttributes = array_unique(array_merge($this->withAttributes, $defaultAttributes));
            if (count($withAttributes) == 1)
            {
                return $model->with($withAttributes[0]);
            }
            else
            {
                return call_user_func_array(array($model, 'with'), $withAttributes);
            }
        }
        else
        {
            return $model;
        }
    }

    /**
     * Apply entity range to fetch
     *
     * @param  mixed  $model
     * @return mixed
     */
    protected function _applyLimit($model)
    {
        if ($this->offset || $this->count >= 0)
        {
            $query = $model;
            if ($this->offset)
            {
                $query = $query->skip($this->offset);
            }
            if ($this->count >= 0)
            {
                $query = $query->take($this->count);
            }
            return $query;
        }
        else
        {
            return $model;
        }
    }

   /**
     * Check must apply any joins
     *
     * @deprecated since version 1.5.0
     *
     * @return boolean
     */
    public function hasJoins()
    {
        return $this->_hasJoins();
    }

    /**
     * Add table prefix if query has applied any joins.
     *
     * @param  array    $columns
     * @return array
     */
    protected function _resolveColumns($columns)
    {
        if ($this->hasJoins())
        {
            $tablePrefix = $this->_table() . '.';
            return array_map(
                function($column) use ($tablePrefix) { 
                    return $tablePrefix . $column; 
                }, $columns);
        }
        else
        {
            return $columns;
        }
    }

    /**
     * Get the default with-Attributes
     *
     * @param  string  $eager
     * @return array
     */ 
    protected function _eagerWithAttributes($eager)
    {
        $withAttributes = [];
        return $withAttributes;
    }
    
    /**
     * Get the default columns
     *
     * @param  string  $eager
     * @return array
     */ 
    protected function _eagerColumns($eager)
    {
        $columns = ['*'];
        return $columns;
    }

    /**
     * Build Eloquent Query for count operation
     *
     * @return mixed
     */ 
    protected function _queryForCount()
    {
        $query = $this->_applyJoins($this->_model(), $this->_table());
        $query = $this->_applyFilters($query, $this->_table());
        $query = $this->_applyLimit($query);
        return $query;
    }

    /**
     * Build Eloquent Query for select operations
     *
     * @param  string  $eager
     * @return mixed
     */ 
    protected function _query($eager)
    {
        $query = $this->_applyJoins($this->_model(), $this->_table());
        $query = $this->_applyFilters($query, $this->_table());
        $query = $this->_applySortings($query, $this->_table());
        $query = $this->_applyWithAttributes($query, $this->_eagerWithAttributes($eager));
        $query = $this->_applyLimit($query);
        return $query;
    }

    /**
     * Count total number of entries
     *
     * Remarks: offset and limit will be ignored
     *
     * @return int
     */ 
    public function total()
    {
        $query = $this->_applyJoins($this->_model(), $this->_table());
        $query = $this->_applyFilters($query, $this->_table());
        return $query->count();
    }
    
    /**
     * Count number of entries
     *
     * @return int
     */ 
    public function count()
    {
        return $this->_queryForCount()->count();
    }
    
    /**
     * Check if there are any entries matching the criterias
     *
     * @return bool
     */ 
    public function exists()
    {
        return $this->_queryForCount()->exists();
    }

}
