<?php
/**
 * Created by PhpStorm.
 * User: enriqueramirez
 * Date: 11/05/16
 * Time: 12:51 PM
 */

namespace messaging\shared\helpers;


class GoogleMaps
{
    /**
     * Cambia las palabras lat y lng por latitud y longitud
     * @param $evaluateString
     * @return mixed
     */
    public static function replaceLatLng($evaluateString)
    {

        $pattern = [
            '/lat/',
            '/lng/'
        ];

        $replacement = [
            'latitude',
            'longitude'
        ];

        return preg_replace($pattern, $replacement, $evaluateString);
    }

    /**
     * Obtiene la distancia entre 2 puntos en base a la latitude y longitude
     * @param $latOrigin
     * @param $lonOrigin
     * @param $latDestination
     * @param $lonDestination
     * @return float
     */
    public static function getDistanceBetweenPoints($latOrigin, $lonOrigin, $latDestination, $lonDestination)
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