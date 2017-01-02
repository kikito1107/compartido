<?php

namespace messaging\shared\services {

    use app\models\Posibilities;
    use app\models\Routes;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 10/10/16
     * Time: 13:06
     */
    class SearchRouteService
    {
        /**
         * Constante para cuando no hay rutas cercanos
         */
        const NOT_AVAILABLE = 6002;

        /**
         * @var string $query
         */
        private $query;

        /**
         * SearchRouteService constructor.
         * @param string $query
         */
        public function __construct($query)
        {
            $this->query = $query;
        }

        /**
         * Busca todas las rutas y posibilidades que contengan en el nombre el criterio de búsqueda dado
         * @return array
         */
        public function run()
        {
            $posibilities = Posibilities::find()->innerJoin('{{%routes}} b', '{{%posibilities}}.routes_id = b.id')
                ->where(['like', '{{%posibilities}}.name', $this->query])
                ->orFilterWhere(['like', 'b.name', $this->query])->all();

            $final = [];
            // Eliminamos los valores repetidos
            foreach ($posibilities as $posibility) {
                if(!in_array($posibility, $final)) {
                    array_push($final, $posibility);
                }
            }

            return $this->getUniqueRoutes($final);
        }

        /**
         * Devuelve un arreglo con las rutas cercanas y solo las posibilidades cercanas a el
         * @param array $data
         * @return array
         */
        private function getUniqueRoutes(array $data)
        {
            if(empty($data)) {
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
                    if($posibility->routes_id == $route->id) {
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