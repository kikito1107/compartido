<?php

namespace messaging\shared\services {

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 24/08/16
     * Time: 12:07 PM
     */
    class Route
    {

        /**
         * @var int $id Identificador de la ruta
         */
        public $id;

        /**
         * @var String $name Nombre de la ruta
         */
        public $name;

        /**
         * @var String $region Nombre de la región
         */
        public $region;

        /**
         * @var array|object $posibilities Rutas posibles
         */
        public $posibilities;

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         * @return $this
         */
        public function setId($id)
        {
            $this->id = $id;
            return $this;
        }

        /**
         * @return String
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param String $name
         * @return $this
         */
        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }

        /**
         * @return String
         */
        public function getRegion()
        {
            return $this->region;
        }

        /**
         * @param String $region
         * @return $this
         */
        public function setRegion($region)
        {
            $this->region = $region;
            return $this;
        }

        /**
         * @return array
         */
        public function getPosibilities()
        {
            return $this->posibilities;
        }

        /**
         * @param array|object $posibilities
         * @return $this
         */
        public function setPosibilities($posibilities)
        {
            $this->posibilities = $posibilities;
            return $this;
        }
    }
}