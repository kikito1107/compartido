<?php

namespace messaging\shared\presenters {

    /**
     * Created by PhpStorm.
     * User: aldorodriguez
     * Date: 11/04/16
     * Time: 12:29 PM
     */
    class MaterialDesignPresenter
    {
        /**
         * Devuelve el template para un input con material design
         * @return string
         */
        public static function getInputTemplate()
        {
            $template = <<<EOF
<div class="mda-form-group float-label">
    <div class="mda-form-control">
        {input}
        <div class="mda-form-control-line"></div>
        {label}
    </div>
    {error}
</div>
EOF;
            return $template;
        }

        /**
         * Devuelve el template para un checkbox con material design
         * @return string
         */
        public static function getCheckboxTemplate()
        {
            $template = <<<EOF
<label class="switch switch-warn switch-success">
    {input}
    <span></span>
</label>
{label}
EOF;
            return $template;
        }

        /**
         * Devuelve el template para un select con material design
         * @return string
         */
        public static function getDropDownListTemplate()
        {
            $template = <<<EOF
<div class="mda-form-group">
    <div class="mda-form-control">
        {input}
        <div class="mda-form-control-line"></div>
        {label}
    </div>
    {error}
</div>
EOF;
            return $template;
        }

        /**
         * Devuelve el template para un input con icono en material design
         * @param $icon
         * @return string
         */
        public static function getInputIconTemplate($icon)
        {
            $template = <<<EOF
<div class="mda-form-group float-label mb mda-input-group">
    <div class="mda-form-control">
        {input}
        <div class="mda-form-control-line"></div>
        {label}
    </div>
    <span class="mda-input-group-addon"><em class="fa {$icon}"></em></span>
    {error}
</div>
EOF;
            return $template;
        }
    }
}