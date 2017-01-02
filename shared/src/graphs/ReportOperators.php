<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 01/09/16
 * Time: 12:39 PM
 */

namespace messaging\shared\graphs {


    use app\models\Posibilities;
    use app\models\Profile;
    use app\models\Region;
    use app\models\RouteHistory;
    use app\models\Routes;
    use messaging\shared\helpers\Dates;
    use yii\helpers\Json;

    class ReportOperators extends Report implements ReportInterface
    {
        /**
         * @var $_to_date Fecha de fin
         */
        public $_to_date;

        /**
         * @var $_from_date Fecha de inicio
         */
        public $_from_date;

        /**
         * Devuelve los datos que se
         * van a mostrar en la tabla
         * @return mixed
         */
        public function build()
        {
            $cards = [];
            $data = [];
            $time_to_date = strtotime($this->getParams()['to_date']);
            $time_from_date = strtotime($this->getParams()['from_date']);
            $this->_to_date = date('Y-m-d', $time_to_date);
            $this->_from_date = date('Y-m-d', $time_from_date);

            $region = Region::findOne([$this->getParams()['region_id']]);

            if(isset($this->getParams()['route_id'])) {
                $routes = Routes::find()->where(['id' => $this->getParams()['route_id']])
                    ->andFilterWhere(['region_id' => $this->getParams()['region_id']])->all();
            } else {
                $routes = Routes::find()->where(['region_id' => $this->getParams()['region_id']])->all();
            }

            $operators = Profile::find()->where(['user_type' => Profile::USER_OPERATOR])->all();
            
            foreach ($operators as $operator) {
                foreach ($routes as $route) {
                    $histories = RouteHistory::find()
                        ->where(['posibility_id' => $route->id])
                        ->andFilterWhere(['operator_profile_id' => $operator->id])
                        ->andFilterWhere(['between', 'end_date', $this->_from_date, $this->_to_date])
                        ->andFilterWhere(['status' => RouteHistory::END])
                        ->all();

                    $cardOperator = new CardOperators();
                    /** @noinspection PhpUndefinedMethodInspection */
                    $cardOperator->setName($operator->getFullName());
                    $cardOperator->setRegion($region->name);
                    /** @noinspection PhpUndefinedFieldInspection */
                    $cardOperator->setRoute($route->name);
                    $average = $this->calculateAverage($histories);
                    $cardOperator->setAverage($average);
                    if ($average != 0) {
                        array_push($cards, $cardOperator);
                    }

                }
            }

            $this->setData($cards);
        }

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
                return number_format($_avergae, 2) . " horas";
            }
        }
    }
}