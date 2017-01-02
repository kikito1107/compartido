<?php

namespace messaging\shared\helpers {

    use Yii;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 25/08/16
     * Time: 10:22 AM
     */
    class HttpRequest
    {
        /**
         * Tratamiento de datos enviados por POST para evitar problemas de compatibilidad entre PHP y Retrofit (Android)
         * @return array|mixed
         */
        public static function post()
        {
            $post = Yii::$app->request->post();

            if ($post == null) {
                $request = json_decode(Yii::$app->request->getRawBody());
                if (isset($request)) {
                    $post = get_object_vars($request);
                }
            }

            return $post;
        }
    }
}