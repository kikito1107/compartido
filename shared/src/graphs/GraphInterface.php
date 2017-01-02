<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 25/08/16
 * Time: 12:07 PM
 */
namespace messaging\shared\graphs {


    interface GraphInterface
    {
        /**
         * Devuelve los datos que se van a graficar
         * @return mixed
         */
        public function build();
    }
}