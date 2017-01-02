<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 01/09/16
 * Time: 12:36 PM
 */

namespace messaging\shared\graphs {


    class Report
    {
        /**
         * @var string $title Título del reporte
         */
        public $title;

        /**
         * @var string $data Datos del reporte
         */
        public $data;

        /**
         * @var array $params Parametros para realizar la consulta de datos
         */
        public $params;

        /**
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @param string $title
         * @return $this
         */
        public function setTitle($title)
        {
            $this->title = $title;
            return $this;
        }

        /**
         * @return string
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * @param string $data
         * @return $this
         */
        public function setData($data)
        {
            $this->data = $data;
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
         * @param array $params
         * @return $this
         */
        public function setParams($params)
        {
            $this->params = $params;
            return $this;
        }
    }
}