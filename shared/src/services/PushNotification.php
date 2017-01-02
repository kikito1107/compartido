<?php
/**
 * Created by PhpStorm.
 * User: wsense
 * Date: 29/08/16
 * Time: 5:41 PM
 */

namespace messaging\shared\services;


use linslin\yii2\curl\Curl;
use Yii;

class PushNotification
{
    /**
     * URL de api Firebase para enviar notificaciones push
     */
    const URL_FCM = "https://fcm.googleapis.com/fcm/send";

    /**
     * Envía una notificación push a los dispositvos que coincidan con los tokens obtenidos
     * @param $tokens
     * @param $message
     * @return mixed
     */
    public function send($tokens, $message)
    {
        $fields = [
            'registration_ids' => $tokens,
            'data' => $message
        ];

        $headers = [
            'Authorization:key = ' . Yii::$app->params['googleApiKey'],
            'Content-Type: application/json'
        ];

        $curl = new Curl();

        $result = $curl->setOption(CURLOPT_POSTFIELDS, json_encode($fields))
            ->setOption(CURLOPT_HTTPHEADER, $headers)
            ->post($this::URL_FCM);

        if(is_object(json_decode($result))) {
            return ["status" => "success", "message" => "Se ha enviado la notificación"];
        } else {
            return ["status" => "error", "message" => "Error al enviar la notificación"];
        }
    }
}