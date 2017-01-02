<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 31/08/16
 * Time: 1:25 PM
 */

namespace messaging\shared\graphs {
    use app\models\Posibilities;
    use app\models\Region;
    use app\models\RouteHistory;
    use messaging\shared\helpers\Dates;
    class GraphByKm extends Graph implements GraphInterface
    {

        /**
         * Devuelve los datos que se van a graficar
         * @return mixed
         */
        public function build()
        {
            $data = [];
            $categories = [];
            $evaluatedMonths = $this->evaluatedMonths();
            $region = Region::findOne([$this->getParams()['regionId']]);
            /** @noinspection PhpUndefinedFieldInspection */
            $routes = $region->routes;

            if(empty($routes)) {
                return;
            }

            foreach ($routes as $route) {
                $serie = new \stdClass();
                $serie->name = $route->name;
                $values = [];
                foreach ($evaluatedMonths as $evaluatedMonth) {
                    $posibilities = Posibilities::find()
                    ->where(['routes_id' => $route->id,
                        'YEAR(create_date)' => $evaluatedMonth->year,
                        'MONTH(create_date)' => $evaluatedMonth->month])->all();
                    $km = 0;
                    foreach ($posibilities as $posibility) {
                        $_km = str_replace(",", ".", $posibility->km);
                        $km += $_km;
                    }
                    array_push($values, $km);
                    array_push($categories, $evaluatedMonth->name);
                }
                $serie->data = $values;
                array_push($data, $this->stdToArray($serie));
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
}