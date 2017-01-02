<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 25/08/16
 * Time: 12:12 PM
 */

namespace messaging\shared\graphs;


use app\models\Posibilities;
use app\models\Region;
use app\models\RouteHistory;
use app\models\Routes;
use messaging\shared\helpers\Dates;
use messaging\shared\services\Route;
use yii\db\Query;

class GraphByTime extends Graph implements GraphInterface
{

    /**
     * Valor cuando se pide por regiones
     */
    const REGION = 1000;

    /**
     * Valor cuando se pide rutas de una region establecida
     */
    const ROUTE = 1001;

    /**
     * @var $_to_date Fecha de fin
     */
    public $_to_date;

    /**
     * @var $_from_date Fecha de inicio
     */
    public $_from_date;

    /**
     * Devuelve los datos que se van a graficar
     * @return mixed
     */
    public function build()
    {
        $time_to_date = strtotime($this->getParams()['to_date']);
        $time_from_date = strtotime($this->getParams()['from_date']);
        $this->_to_date = date('Y-m-d H:i:s', $time_to_date);
        $this->_from_date = date('Y-m-d H:i:s', $time_from_date);

        $data = [];
        $categories = [];
        $evaluatedMonths = $this->evaluatedMonths();
        foreach ($evaluatedMonths as $key => $evaluatedMonth) {
            $values = [];
            if($this->getParams()['regionId'] != "") {
                $region = Region::findOne($this->getParams()['regionId']);
                $histories = RouteHistory::find()
                    ->where(['IN', 'route_id', $region->getRoutesIds()])
                    ->andFilterWhere([
                        'YEAR(end_date)' => $evaluatedMonth->year,
                        'MONTH(end_date)' => $evaluatedMonth->month,
                        'status' => RouteHistory::END
                    ])->all();

                $average = $this->calculateAverage($histories);

                $serie = new \stdClass();
                $serie->name = $region->name;
                $serie->data = (array)floatval($average);
                array_push($data, $this->stdToArray($serie));
            } else {
                $regions = Region::findAll(['status' => Region::STATUS_ACTIVE]);

                foreach ($regions as $region) {
                    $histories = RouteHistory::find()
                        ->where(['IN', 'route_id', $region->getRoutesIds()])
                        ->andFilterWhere([
                            'YEAR(end_date)' => $evaluatedMonth->year,
                            'MONTH(end_date)' => $evaluatedMonth->month,
                            'status' => RouteHistory::END
                        ])->all();

                    $average = $this->calculateAverage($histories);

                    $serie = new \stdClass();
                    $serie->name = $region->name;
                    $serie->data = (array)floatval($average);
                    array_push($data, $this->stdToArray($serie));
                }

            }

            array_push($categories, $evaluatedMonth->name);
        }

        $this->setCategories($categories);
        $this->setData($data);
    }

    /**
     * Funcion para traer el arreglo de meses
     * @return array
     */
    private function evaluatedMonths()
    {
        $evaluateMonths = [];
        // Fecha inicial separada
        $partsStartDate = explode("-", $this->getParams()['from_date']);
        // Fecha final separada
        $partsEndDate = explode("-", $this->getParams()['to_date']);

        // Arreglo de meses
        $months = Dates::getNumberMonths();
        // Posición del mes en el arreglo para fecha inicial
        $initialKey = array_search($partsStartDate[1], $months);
        // Posición del mes en el arreglo para fecha final
        $finalKey = array_search($partsEndDate[1], $months);
        // Diferencia de años entre una fehca y otra
        $yearDiff = $partsEndDate[2] - $partsStartDate[2];

        if($partsStartDate[2] == $partsEndDate[2]) {
            $_months = array_splice($months, $initialKey, ($finalKey + 1) - $initialKey);
            $evaluateMonths = $this->generateArrayMonths($_months, $partsEndDate[2]);
        }

        if($yearDiff >= 1) {
            $monthsFirstYear = array_splice($months, $initialKey, 12);
            // Volvemos a inicializar el arreglo
            $months = Dates::getNumberMonths();
            $monthsLastYear = array_splice($months, 0, $finalKey+1);
            $evaluateFisrtsMonths = $this->generateArrayMonths($monthsFirstYear, $partsStartDate[2]);
            $evaluateLastMonths = $this->generateArrayMonths($monthsLastYear, $partsEndDate[2]);

            if($yearDiff > 1) {
                $arrayMonths = [];
                // Obtenemos los meses de los años intermedios
                for ($i = $partsStartDate[2] + 1; $i < $partsEndDate[2]; $i++) {
                    $middleMonths = $this->generateArrayMonths(Dates::getNumberMonths(), $i);
                    foreach ($middleMonths as $month) {
                        array_push($arrayMonths, $month);
                    }
                }

                $evaluateMonths = array_merge($evaluateFisrtsMonths, $arrayMonths, $evaluateLastMonths);
            } else {
                $evaluateMonths = array_merge($evaluateFisrtsMonths, $evaluateLastMonths);
            }
        }

        return $evaluateMonths;
    }


    /**
     * Genera un arreglo de objetos que contienen información del mes y año que será consultado
     * @param $months
     * @param $year
     * @return array
     */
    private function generateArrayMonths($months, $year)
    {
        $arrayMonths = [];
        foreach ($months as $month) {
            $monthYear = new \stdClass();
            $monthYear->month = $month;
            $monthYear->year = $year;
            $monthYear->name = Dates::getMonths()[$month] . ' - ' . $year;
            array_push($arrayMonths, $monthYear);
        }

        return $arrayMonths;
    }

    /**
     * Función pra sacar el promedio de tiempo de las rutas o regiones
     * @param $posibility
     * @return array
     */
    private function calculateAverage(array $histories)
    {
        $minutes = [];
        foreach ($histories as $history) {
            $times = explode('|', $history->average_time);
            $daysToMinutes = $times[0] * 1440;
            $hours = $times[1] * 60;
            $totalMinutes = $daysToMinutes + $hours + $times[2];
            array_push($minutes, $totalMinutes);
        }

        $total = count($histories);
        if ($total == 0) {
            return 0;
        } else {
            $average = array_sum($minutes) / count($histories);
            $_avergae = $average / 60;
            return number_format($_avergae, 2);
        }
    }

    /**
     * Convertimos el objeto stdObject a un array
     * @param $obj
     * @return array
     */
    private function stdToArray($obj){
        $reaged = (array)$obj;
        foreach($reaged as $key => &$field){
            if(is_object($field))$field = stdToArray($field);
        }
        return $reaged;
    }
}