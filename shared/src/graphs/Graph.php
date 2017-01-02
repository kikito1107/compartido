<?php
namespace messaging\shared\graphs {

    /**
     * Created by PhpStorm.
     * User: enriqueramirez
     * Date: 25/08/16
     * Time: 11:53 AM
     */
    class Graph
    {
        /**
         * @var string $title Título de la gráfico
         */
        public $title;

        /**
         * @var string $subtitle Subtitulo de la gráfica
         */
        public $subtitle;

        /**
         * @var string $type Tipo de gráfica
         */
        public $type;

        /**
         * @var string $titleX Título del eje de las X
         */
        public $titleX;

        /**
         * @var string $titleY Título del eje de las Y
         */
        public $titleY;

        /**
         * @var string $data Datos a graficar
         */
        public $data;

        /**
         * @var string $categories Categorías para graficar
         */
        public $categories;

        /**
         * @var array $params Parametros para realizar la consulta de datos
         */
        public $params;

        /**
         * @param $title
         * @return $this
         */
        public function setTitle($title)
        {
            $this->title = $title;
            return $this;
        }

        /**
         * @param $subtitle
         * @return $this
         */
        public function setSubtitle($subtitle)
        {
            $this->subtitle = $subtitle;
            return $this;
        }

        /**
         * @param $type
         * @return $this
         */
        public function setType($type)
        {
            $this->type = $type;
            return $this;
        }

        /**
         * @param $titleX
         * @return $this
         */
        public function setTitleX($titleX)
        {
            $this->titleX = $titleX;
            return $this;
        }

        /**
         * @param $titleY
         * @return $this
         */
        public function setTitleY($titleY)
        {
            $this->titleY = $titleY;
            return $this;
        }

        /**
         * @param $data
         * @return $this
         */
        public function setData($data)
        {
            $this->data = $data;
            return $this;
        }

        /**
         * @param $categories
         * @return $this
         */
        public function setCategories($categories)
        {
            $this->categories = $categories;
            return $this;
        }

        /**
         * @return array
         */
        public function getParams()
        {
            return $this->params;
        }

        /**
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getSubtitle()
        {
            return $this->subtitle;
        }

        /**
         * @return string
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * @return string
         */
        public function getTitleX()
        {
            return $this->titleX;
        }

        /**
         * @return string
         */
        public function getTitleY()
        {
            return $this->titleY;
        }

        /**
         * @return string
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * @return string
         */
        public function getCategories()
        {
            return $this->categories;
        }

        /**
         * @param $params
         * @return $this
         */
        public function setParams($params)
        {
            $this->params = $params;
            return $this;
        }
    }
}