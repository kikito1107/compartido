<?php

namespace messaging\shared\services {

    use app\models\NearbyOperatorRequest;
    use app\models\RouteHistory;
    use yii\helpers\Url;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 05/09/16
     * Time: 5:41 PM
     */
    class NearbyOperatorService
    {
        /**
         * @var NearbyOperatorRequest $nearbyOperatorRequest
         */
        public $nearbyOperatorRequest;

        /**
         * Constante para cuando no hay operadores cercanos
         */
        const NOT_AVAILABLE = 2002;

        /**
         * Estatus para petición terminada con éxito
         */
        const SUCCESS = 2001;

        /**
         * NearbyOperatorService constructor.
         * @param NearbyOperatorRequest $nearbyOperatorRequest
         */
        public function __construct(NearbyOperatorRequest $nearbyOperatorRequest)
        {
            $this->nearbyOperatorRequest = $nearbyOperatorRequest;
        }

        public function run()
        {
            $routeHistory = RouteHistory::find()
                ->where(['status' => RouteHistory::START])
                ->andFilterWhere(['posibility_id' => $this->nearbyOperatorRequest->posibilityId])
                ->andFilterWhere(['<>', 'operator_profile_id', $this->nearbyOperatorRequest->userId])
                ->all();

            if (empty($routeHistory)) {
                return ['status' => $this::NOT_AVAILABLE];
            }

            return $this->getNearest($routeHistory);

        }

        private function getNearest(array $operators)
        {
            $available = ['status' => $this::SUCCESS];
            $available['operators']['operator'] = [];
            /** @noinspection PhpUndefinedMethodInspection */
            $latitude = $this->nearbyOperatorRequest->getProfile()->getLatitude();
            /** @noinspection PhpUndefinedMethodInspection */
            $longitude = $this->nearbyOperatorRequest->getProfile()->getLongitude();

            foreach ($operators as $operator) {
                $distance =
                    $this->getDistanceBetweenPoints($latitude, $longitude, $operator->profile->getLatitude(), $operator->profile->getLongitude());
                if ($distance <= $this->nearbyOperatorRequest->radius) {
                    $profile = new \stdClass();
                    $profile->name = $operator->profile->getFullName();
                    $profile->phone = $operator->profile->phone;
                    $profile->photo = Url::to('@web/profile/avatar?id=' . $operator->profile->id, true);
                    $profile->location = $operator->profile->last_known_location;
                    array_push($available['operators']['operator'], $profile);
                }
            }

            return $available;
        }

        /**
         * Obtiene la distancia entre 2 puntos en base a la latitude y longitude
         * @param $latOrigin
         * @param $lonOrigin
         * @param $latDestination
         * @param $lonDestination
         * @return float
         */
        private function getDistanceBetweenPoints($latOrigin, $lonOrigin, $latDestination, $lonDestination)
        {
            $theta = $lonOrigin - $lonDestination;
            $distance = (sin(deg2rad($latOrigin)) * sin(deg2rad($latDestination))) + (cos(deg2rad($latOrigin)) * cos(deg2rad($latDestination)) * cos(deg2rad($theta)));
            $distance = acos($distance);
            $distance = rad2deg($distance);
            $distance = $distance * 60 * 1.1515;
            $distance = $distance * 1.609344;

            return (round($distance, 2));
        }
    }
}