<?php

namespace messaging\shared\helpers {

    use Yii;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 13/04/16
     * Time: 6:02 PM
     */
    class Dates
    {
        /**
         * Convierte una fecha en formato para guardar en mysql Y-m-d
         * @param $date
         * @return mixed
         */
        public static function convertSqlDate($date)
        {
            $time = strtotime($date);
            $newDate = date('Y-m-d', $time);
            return $newDate;
        }

        /**
         * Meses como números
         * @return array
         */
        public static function getNumberMonths()
        {
            return [
                "1",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9",
                "10",
                "11",
                "12",
            ];
        }

        /**
         * Arreglo con meses del año
         * @return array
         */
        public static function getMonths()
        {
            return [
                1 => Yii::t('app', 'Enero'),
                2 => Yii::t('app', 'Febrero'),
                3 => Yii::t('app', 'Marzo'),
                4 => Yii::t('app', 'Abril'),
                5 => Yii::t('app', 'Mayo'),
                6 => Yii::t('app', 'Junio'),
                7 => Yii::t('app', 'Julio'),
                8 => Yii::t('app', 'Agosto'),
                9 => Yii::t('app', 'Septiembre'),
                10 => Yii::t('app', 'Octubre'),
                11 => Yii::t('app', 'Noviembre'),
                12 => Yii::t('app', 'Diciembre')
            ];
        }

        /* Regresa la fecha actual con formato SQL
         * @return false|string
         */
        public static function now()
        {
            return date('Y-m-d H:i:s');
        }

        /**
         * Devuelve un string con el tiempo transcurrido entre 2 fechas
         * @param $start
         * @param $end
         * @return string
         */
        public static function elapsedTimeBetweenDates($start, $end, $text)
        {
            $_start = new \DateTime($start);
            $_end = new \DateTime($end);
            $diff = date_diff($_start, $_end);
            $days = $diff->days;
            $hours = $diff->h;
            $minutes = $diff->i;

            if($text) {
                return "Tiempo transcurrido: {$days} días, {$hours} horas y {$minutes} minutos";
            } else{
                return "{$days}|{$hours}|{$minutes}";
            }
        }
    }
}