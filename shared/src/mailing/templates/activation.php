<tr>
    <td class="column" style="padding: 0;vertical-align: middle;text-align: left;">
        <div>
            <div class="column-top"
                 style="font-size: 40px;line-height: 40px;transition-timing-function: cubic-bezier(0, 0, 0.2, 1);transition-duration: 150ms;transition-property: all;">
                &nbsp;</div>
        </div>
        <table class="contents"
               style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;">
            <tbody>
            <tr>
                <td class="padded"
                    style="padding: 0;vertical-align: middle;padding-left: 56px;padding-right: 56px;word-break: break-word;word-wrap: break-word;">

                    <h3 style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 0;font-size: 16px;line-height: 24px;font-family: &quot;PT Serif&quot;,Georgia,serif;color: #788991;text-align: center;">
                        BIENVENIDO</h3>

                    <h1 style="font-style: normal;font-weight: 400;Margin-bottom: 0;Margin-top: 14px;font-size: 22px;line-height: 30px;font-family: Ubuntu,sans-serif;color: #3e4751;text-align: center;">
                        Activa tu cuenta</h1>

                    <p style="font-style: normal;font-weight: 400;Margin-bottom: 22px;Margin-top: 18px;font-size: 13px;line-height: 22px;font-family: &quot;PT Serif&quot;,Georgia,serif;color: #7c7e7f;text-align: left;">
                        Para terminar tu registro debes de activar tu cuenta dando clic en el siguiente botón</p>

                    <p style="font-style: normal;font-weight: 400;Margin-bottom: 22px;Margin-top: 18px;font-size: 13px;line-height: 22px;font-family: &quot;PT Serif&quot;,Georgia,serif;color: #7c7e7f;text-align: left;">
                        Tu contraseña temporal es: <?php echo $content['pwd'] ?></p>

                </td>
            </tr>
            </tbody>
        </table>

        <table class="contents"
               style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;">
            <tbody>
            <tr>
                <td class="padded"
                    style="padding: 0;vertical-align: middle;padding-left: 56px;padding-right: 56px;word-break: break-word;word-wrap: break-word;">

                    <div class="btn btn--center"
                         style="Margin-bottom: 0;Margin-top: 0;text-align: center;">
                        <a
                            style="border-radius: 3px;display: inline-block;font-size: 14px;font-weight: 700;line-height: 24px;padding: 13px 35px 12px 35px;text-align: center;text-decoration: none !important;transition: opacity 0.2s ease-in;font-family: &quot;PT Serif&quot;,Georgia,serif;background-color: #4eaacc;color: #fff;"
                            href="<?php echo 'http://'. $_SERVER['SERVER_NAME'] . '/index.php/user/activate?id='. $content['user']->id .'&authKey='. $content['user']->authKey?>">ACTIVAR</a>
                    </div>

                </td>
            </tr>
            </tbody>
        </table>

        <div class="column-bottom"
             style="font-size: 40px;line-height: 40px;transition-timing-function: cubic-bezier(0, 0, 0.2, 1);transition-duration: 150ms;transition-property: all;">
            &nbsp;</div>
    </td>
</tr>