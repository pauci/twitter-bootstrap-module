<?php

/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormButton as BaseFormButton;

class FormButton extends BaseFormButton
{
    public function createAttributesString(array $attributes)
    {
        $attributes['class'] = isset($attributes['class'])
            ? $attributes['class'] . ' btn' : 'btn';
        return parent::createAttributesString($attributes);
    }
}
