<?php

/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormLabel as BaseFormLabel;

class FormLabel extends BaseFormLabel
{
    /**
     * Override of original method to add Twitter Bootstrap css class
     *
     * @param array $attributes
     * @return string
     */
    public function createAttributesString(array $attributes)
    {
        if ($this->getLayout() == FormLayout::LAYOUT_HORIZONTAL) {
            $attributes['class'] = isset($attributes['class'])
                ? $attributes['class'] . ' control-label'
                : 'control-label';
        }
        return parent::createAttributesString($attributes);
    }

    /**
     * Retrieve Twitter Bootstrap layout configured via FormLayout view helper
     *
     * @return string
     */
    protected function getLayout()
    {
        if (null === $this->formLayoutHelper) {
            $renderer = $this->getView();
            if (method_exists($renderer, 'plugin')) {
                $this->formLayoutHelper = $renderer->plugin('form_layout');
            }
        }
        return $this->formLayoutHelper
            ? $this->formLayoutHelper->getLayout()
            : FormLayout::LAYOUT_DEFAULT;
    }
}
