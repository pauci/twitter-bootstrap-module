<?php

/*
 * @copyright Copyright (c) 2012 Pavol Kirschbaum
 * @license   Internal use only
 * @author    Pavol Kirschbaum
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormSubmit as BaseFormSubmit;

/**
 * Description of FormSubmit
 *
 * @author Pavol Kirschbaum <pauci.sk@gmail.com>
 */
class FormSubmit extends BaseFormSubmit
{
    public function createAttributesString(array $attributes)
    {
        $attributes['class'] = isset($attributes['class'])
            ? $attributes['class'] . ' btn' : 'btn';
        return parent::createAttributesString($attributes);
    }
}
