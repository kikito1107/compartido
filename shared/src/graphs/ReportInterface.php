<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 01/09/16
 * Time: 12:38 PM
 */

namespace messaging\shared\graphs {


    interface ReportInterface
    {
        /**
         * Devuelve los datos que se
         * van a mostrar en la tabla
         * @return mixed
         */
        public function build();
    }
}