<?php
/**
 * Created by PhpStorm.
 * User: wsense
 * Date: 12/04/16
 * Time: 5:21 PM
 */

namespace messaging\shared\mailing;


class MailAttributes
{
    /**
     * @var array $receivers Receptores
     */
    public $receivers;

    /**
     * @var string $subject Asunto del mensaje
     */
    public $subject;

    /**
     * @var array $attachments Archivos adjuntos
     */
    public $attachments;

    /**
     * @var string $template Plantilla de correo electrónico
     */
    public $template;

    /**
     * @var array $data Información adicional
     */
    public $data;

    // Subjects para las diferentes notificaciones

    // Activación de cuenta
    const SUBJECT_ACTIVATION = "Activación de cuenta";

    // Templates disponibles

    // Plantilla de activación
    const TMPL_ACTIVATION = "activation";

    /**
     * @return array
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * @param array $receivers
     * @return $this
     */
    public function setReceivers($receivers)
    {
        $this->receivers = $receivers;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}