<?php
/**
 * Created by PhpStorm.
 * User: wsense
 * Date: 12/04/16
 * Time: 5:21 PM
 */

namespace messaging\shared\mailing;


class Mail
{
    /**
     * Correo que envía las notificaciones por default
     */
    const SENDER = "notifications@messaging.com";

    /**
     * @param MailAttributes $attributes
     * @return mixed
     */
    public function send(MailAttributes $attributes)
    {
        $path = \Yii::getAlias('@app') . DIRECTORY_SEPARATOR . "web/uploads" . DIRECTORY_SEPARATOR;

        $pathTemplate = '@vendor/messaging/shared/src/mailing/templates/main.php';

        $template = \Yii::$app->view->render($pathTemplate, [
            'content' => $attributes->getData(),
            'template' => $attributes->getTemplate()
        ]);

        $mail = \Yii::$app->mailer->compose();
        $mail->setFrom($this::SENDER)
            ->setTo($attributes->getReceivers())
            ->setSubject($attributes->getSubject())
            ->setHtmlBody($template);

        if(!empty($attributes->getAttachments()))
        {
            foreach ($attributes->getAttachments() as $attachment) {
                $mail->attachContent(file_get_contents($path . $attachment->path),
                    [
                        'fileName' => $attachment->path,
                        'contentType' => mime_content_type($path . $attachment->path)
                    ]);
            }
        }

        $mail->send();
    }
}