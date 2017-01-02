<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 02/09/16
 * Time: 11:21 AM
 */

namespace messaging\shared\graphs;


class CardOperators
{
    /**
     * @var String $name Nombre del operador
     */
    public $name;

    /**
     * @var String $region Nombre de la región en que opera
     */
    public $region;

    /**
     * @var String $route Nombre de la ruta que esta asignado
     */
    public $route;

    /**
     * @var Float $average Tiempo promedio de recorrido
     */
    public $average;

    /**
     * CardOperators constructor.
     */
    public function __construct(){}

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param String $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @param String $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @param Float $average
     */
    public function setAverage($average)
    {
        $this->average = $average;
    }
}