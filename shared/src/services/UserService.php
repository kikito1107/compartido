<?php

namespace messaging\shared\services {

    use auth\models\User;
    use messaging\shared\mailing\Mail;
    use messaging\shared\mailing\MailAttributes;
    use Yii;

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 12/04/16
     * Time: 5:16 PM
     */
    class UserService
    {
        /**
         * Estatus inactivo
         */
        const STATUS_INACTIVE = 1;

        /**
         * @var string $email Correo Electrónico
         */
        public $email;

        /**
         * @var string $password Contraseña
         */
        public $password;

        /**
         * @var string $role Rol de usuario
         */
        public $role;

        /**
         * UserService constructor.
         * @param string $email
         * @param string $role
         */
        public function __construct($email, $role)
        {
            $this->email = $email;
            $this->role = $role;
        }

        /**
         * Genera un usuario
         *
         * Creamos una instancia de
         * @return bool|int
         */
        public function registerUser()
        {
            $user = new User();
            $user->username = $this->email;
            $user->email = $this->email;
            $this->password = $this->generatePassword();
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = $this::STATUS_INACTIVE;

            if($user->save()) {

                $auth = Yii::$app->authManager;

                $role = $auth->getRole($this->role);

                $auth->assign($role, $user->id);

                $attributes = new MailAttributes();
                $attributes->setReceivers([$this->email])
                    ->setSubject(MailAttributes::SUBJECT_ACTIVATION)
                    ->setTemplate(MailAttributes::TMPL_ACTIVATION)
                    ->setData(['user' => $user, 'pwd' => $this->password]);

                $mailing = new Mail();
                $mailing->send($attributes);

                return $user->id;
            }

            return false;
        }

        /**
         * Genera un password aleatorio
         * @return string
         */
        private function generatePassword()
        {
            $password = substr(md5(microtime()), 1, 8);

            return $password;
        }
    }
}