<?php

/*
 * @copyright Copyright (c) 2012 Pavol Kirschbaum
 * @license   Internal use only
 * @author    Pavol Kirschbaum
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormReset as BaseFormReset;

/**
 * Description of FormReset
 *
 * @author Pavol Kirschbaum <pauci.sk@gmail.com>
 */
class FormReset extends BaseFormReset
{
    public function createAttributesString(array $attributes)
    {
        $attributes['class'] = isset($attributes['class'])
            ? $attributes['class'] . ' btn' : 'btn';
        return parent::createAttributesString($attributes);
    }
}
