<?php

namespace messaging\shared\services {

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 23/08/16
     * Time: 5:45 PM
     */
    class AssignmentRequest
    {
        /**
         * @var array $profile Perfil del usuario
         */
        private $routes;

        /**
         * AssignmentRequest constructor.
         * @param array $routes
         */
        public function __construct(array $routes)
        {
            $this->routes = $routes;
        }

        /**
         * Genera un json con la información de rutas sobre un operador
         * @return array
         */
        public function run()
        {
            $data['routes'] ['route'] = [];

            foreach ($this->routes as $route) {
                $_route = new Route();
                $_route->setId($route->routes_id)
                    ->setName($route->routes->name)
                    ->setRegion($route->routes->region->name)
                    ->setPosibilities($route->routes->posibilities);

                array_push($data['routes'] ['route'], $_route);
            }

            return $data;
        }
    }
}