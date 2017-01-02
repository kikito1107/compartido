<?php

namespace messaging\shared\services {

    use app\models\NearbyRouteClientRequest;
    use app\models\Posibilities;
    use app\models\Routes;
    use messaging\shared\helpers\GoogleMaps;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 20/09/16
     * Time: 12:00 PM
     */
    class NearbyRouteClientService
    {
        /**
         * @var NearbyRouteClientRequest $nearbyRouteClientRequest
         */
        public $nearbyRouteClientRequest;

        /**
         * Constante para cuando no hay rutas cercanos
         */
        const NOT_AVAILABLE = 6002;

        /**
         * Radio para realizar la busqueda de rutas cercanas
         */
        const RADIO_SEARCH = 1;

        /**
         * NearbyRouteClientService constructor.
         * @param NearbyRouteClientRequest $nearbyRouteClientRequest
         */
        public function __construct(NearbyRouteClientRequest $nearbyRouteClientRequest)
        {
            $this->nearbyRouteClientRequest = $nearbyRouteClientRequest;
        }

        /**
         * @return array
         */
        public function run()
        {
            $posibilities = Posibilities::find()->all();

            if (count($posibilities) < 1) {
                return ['status' => $this::NOT_AVAILABLE];
            }

            $posibilitiesOrigin = $this->getNearbyPosibilities($posibilities, $this->nearbyRouteClientRequest->latOrigin, $this->nearbyRouteClientRequest->lngOrigin, true);
            $posibilitiesDestiny = $this->getNearbyPosibilities($posibilities, $this->nearbyRouteClientRequest->latDestiny, $this->nearbyRouteClientRequest->lngDestiny, false);
            $allPosibilties = array_merge($posibilitiesOrigin, $posibilitiesDestiny);

            $final = [];

            // Eliminamos los valores repetidos
            foreach ($allPosibilties as $posibility) {
                if (!in_array($posibility, $final)) {
                    array_push($final, $posibility);
                }
            }

            return $this->getUniqueRoutes($final);
        }

        /**
         * Devuelve la dirección de la ruta
         * @param $originLatitude
         * @param $destinyLatitude
         * @return string
         */
        private function getDiretionPosibility($originLatitude, $destinyLatitude)
        {
            //Latitud eje Y
            //Si la latitud de origen es menor a la latitud de destino la posibilidad va hacia arriba
            //Si la latitud de origen es mayor a la latitud de destino la posibilidad va hacia abajo
            if ($originLatitude < $destinyLatitude) {
                $orientation = "north";
            } else {
                $orientation = "south";
            }

            return $orientation;
        }

        /**
         * Obtiene las posibilidades cercanas
         * @param array $posibilities
         * @param $latitude
         * @param $longitude
         * @param $origin
         * @return array
         */
        private function getNearbyPosibilities(array $posibilities, $latitude, $longitude, $origin)
        {
            $data = [];

            $userDirections = $this->getDiretionPosibility($this->nearbyRouteClientRequest->latOrigin, $this->nearbyRouteClientRequest->latDestiny);

            // Analizamos las posibilidades para obtener las más cercanas al punto de petición
            foreach ($posibilities as $posibility) {
                /** @noinspection PhpUndefinedFieldInspection */
                $locationStartParts = explode(',', $posibility->location_start);
                $locationEndParts = explode(',', $posibility->location_ending);

                if($userDirections != $this->getDiretionPosibility($locationStartParts[0], $locationEndParts[0])) {
                    continue;
                }

                if($origin) {
                    $distance = GoogleMaps::getDistanceBetweenPoints($latitude, $longitude, $locationStartParts[0], $locationStartParts[1]);
                } else {
                    $distance = GoogleMaps::getDistanceBetweenPoints($latitude, $longitude, $locationEndParts[0], $locationEndParts[1]);
                }

                // Primero verificamos el punto de inicio si la distancia es menor a 1 km, entonces agregamos al arreglo
                if ($distance <= $this::RADIO_SEARCH) {
                    array_push($data, $posibility);
                    continue;
                }

                // Si en el paso anterior no esta cerca, procedemos a buscar los checkpoints para poder sugerir una ruta cercana
                if (!empty($posibility->main_locations)) {
                    $near = 0;
                    foreach ($posibility->main_locations as $main_location) {
                        $distance = GoogleMaps::getDistanceBetweenPoints($latitude, $longitude, $main_location->latitude, $main_location->longitude);

                        if ($distance <= $this::RADIO_SEARCH) {
                            $near++;
                        }
                    }

                    if ($near > 0) {
                        array_push($data, $posibility);
                    }
                }
            }

            return $data;
        }

        /**
         * Devuelve un arreglo con las rutas cercanas y solo las posibilidades cercanas a el
         * @param array $data
         * @return array
         */
        private function getUniqueRoutes(array $data)
        {
            if (empty($data)) {
                return ['status' => $this::NOT_AVAILABLE];
            }

            $routesCollection['routes']['route'] = [];
            $ids = [];

            // Obtenemos los ids de las rutas para realizar una consulta
            foreach ($data as $posibility) {
                array_push($ids, $posibility->routes_id);
            }

            $routes = Routes::find()->where(['IN', 'id', array_unique($ids)])->all();

            foreach ($routes as $route) {
                $_route = new Route();
                $_route->setId($route->id)
                    ->setName($route->name)
                    ->setRegion($route->region->name);
                $posibilities = [];
                foreach ($data as $posibility) {
                    if ($posibility->routes_id == $route->id) {
                        array_push($posibilities, $posibility);
                    }
                }
                $_route->setPosibilities($posibilities);
                array_push($routesCollection['routes']['route'], $_route);
            }

            return $routesCollection;
        }
    }
}