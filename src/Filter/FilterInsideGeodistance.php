<?php

namespace Devpoint\Database\SearchCriterias\Eloquent\Filter;

use Devpoint\Database\SearchCriterias\Eloquent\Filter\AbstractFilter;

class FilterInsideGeodistance extends AbstractFilter {

    /**
     * @var string
     */
    protected $columnLat;
    
    /**
     * @var string
     */
    protected $columnLng;
    
    /**
     * @var float
     */
    protected $lat;

    /**
     * @var float
     */
    protected $lng;

    /**
     * @var float
     */
    protected $distance;

    /**
     * Constructor
     *
     * @param  string  $columnLat
     * @param  string  $columnLng
     * @param  float   $lat
     * @param  float   $lng
     * @param  float   $distance
     * @return void
     */
    public function __construct($columnLat, $columnLng, $lat, $lng, $distance)
    {
        $this->columnLat = $columnLat;
        $this->columnLng = $columnLng;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->distance = $distance;
    }

    /**
     * Find rows inside distance to given geo location
     *
     * SQL query base on:
     * http://gis.stackexchange.com/questions/31628/find-points-within-a-distance-using-mysql
     * @Mapperz, @Marek Čačko
     *
     * Remark:
     * Constant 6371 could be use for distances in km
     * for distances in miles use 3959
     *
     * @param mixed   $model
     * @param string  $boolean - and|or|{empty}
     * @param string  $tableName (optional)
     * @return mixed
     */
    public function apply($model, $boolean, $tableName)
    {
        $tablePrefix = (!empty($tableName)) ? $tableName . '.' : '';
        $columnlat = $tablePrefix . $this->columnLat;
        $columnlng = $tablePrefix . $this->columnLng;
        $lat = $this->lat;
        $lng = $this->lng;
        $distance = $this->distance;
        $sqlStatement  = "(6371 * acos(cos(radians(${lat}))";
        $sqlStatement .= " * cos(radians({$columnlat}))";
        $sqlStatement .= " * cos(radians({$columnlng}) - radians(${lng}))";
        $sqlStatement .= " + sin(radians(${lat})) * sin(radians({$columnlat})))) < {$distance}";
        $whereRawFunc = (!empty($boolean) && $boolean === 'or') ? 'orWhereRaw' : 'whereRaw';
        return $model->$whereRawFunc($sqlStatement);
    }
}
