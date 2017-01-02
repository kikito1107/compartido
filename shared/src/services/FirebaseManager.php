<?php

namespace messaging\shared\services {

    use app\models\FirebaseToken;
    use app\models\Notification;
    use app\models\Profile;
    use app\models\RouteOperator;
    use app\models\Routes;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 29/08/16
     * Time: 5:18 PM
     */
    class FirebaseManager
    {
        /**
         * @var Notification
         */
        private $notification;

        /**
         * @var array $tokens
         */
        private $tokens = [];

        /**
         * FirebaseManager constructor.
         * @param Notification $notification
         */
        public function __construct(Notification $notification)
        {
            $this->notification = $notification;
        }

        /**
         * Envia una notificación push a todos los operadores en base  a los parametros recibidos
         */
        public function pushNotification()
        {
            if (!empty($this->notification->route_id)) {
                $this->byRoute($this->notification->route_id);
            }

            if (!empty($this->notification->region_id) && empty($this->notification->route_id)) {
                $this->byRegion();
            }

            if (empty($this->notification->region_id) && empty($this->notification->route_id)) {
                $this->all();
            }

            $pushNotification = new PushNotification();
            $pushNotification->send(array_unique($this->tokens), ['message' => $this->notification->message]);
        }

        /**
         * Busca todos los operadores que pertenezcan a una ruta especifica
         * @param $route_id
         */
        private function byRoute($route_id)
        {
            $operators = RouteOperator::find()->where(['routes_id' => $route_id])->all();
            foreach ($operators as $operator) {
                /** @noinspection PhpUndefinedFieldInspection */
                $token = FirebaseToken::find()->where(['profile_id' => $operator->profile_id])
                    ->orderBy(['create_date' => SORT_DESC])->one();
                /** @noinspection PhpUndefinedFieldInspection */
                array_push($this->tokens, $token->token);
            }
        }

        /**
         * Busca todos los operadores que pertenezcan a cierta región
         */
        private function byRegion()
        {
            $routes = Routes::find()->where(['region_id' => $this->notification->region_id])->all();
            foreach ($routes as $route) {
                /** @noinspection PhpUndefinedFieldInspection */
                $this->byRoute($route->id);
            }
        }

        /**
         * Busca el token de todos los operadores
         */
        private function all()
        {
            $operators = Profile::find()->where(['user_type' => Profile::USER_OPERATOR])->all();
            foreach ($operators as $operator) {
                /** @noinspection PhpUndefinedFieldInspection */
                $token = FirebaseToken::find()->where(['profile_id' => $operator->id])
                    ->orderBy(['create_date' => SORT_DESC])->one();
                /** @noinspection PhpUndefinedFieldInspection */
                array_push($this->tokens, $token->token);
            }
        }
    }
}