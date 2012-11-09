<?php

/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;

class FormElementHelp extends AbstractHelper
{
    const STYLE_INLINE = 'inline';
    const STYLE_BLOCK  = 'block';

    protected $style = self::STYLE_BLOCK;

    /**
     * @var array Default attributes for the open format tag
     */
    protected $attributes = array();

    /**
     * @param ElementInterface $element
     * @param array $attributes
     * @return string
     */
    public function render(ElementInterface $element, array $attributes = array())
    {
        $description = $element->getOption('description');
        if (!$description) {
            return '';
        }

        // Prepare attributes for opening tag
        $attributes = array_merge($this->attributes, $attributes);
        $attributes = $this->createAttributesString($attributes);
        if (!empty($attributes)) {
            $attributes = ' ' . $attributes;
        }

        if ($element->getOption('escape_description') !== false) {
            // Flatten message array
            $escapeHtml  = $this->getEscapeHtmlHelper();
            $description = $escapeHtml($description);
        }

        // Generate markup
        $markup  = sprintf('<span class="help-%s">', $this->style);
        $markup .= $description;
        $markup .= '</span>';

        return $markup;
    }

    /**
     * @param ElementInterface|null $element
     * @param array $attributes
     * @return FormElementHelp
     */
    public function __invoke(ElementInterface $element = null, array $attributes = array())
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $attributes);
    }

    /**
     * Set the attributes that will go on the message open format
     *
     * @param array key value pairs of attributes
     * @return FormElementHelp
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Get the attributes that will go on the message open format
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $style
     * @return FormElementHelp
     * @throws Exception\InvalidArgumentException
     */
    public function setStyle($style)
    {
        $style = strtolower($style);
        if (!in_array($style, array(self::STYLE_INLINE, self::STYLE_BLOCK))) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s excepts either %s::STYLE_INLINE or %s::STYLE_BLOCK; received "%s"',
                __METHOD__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                (string) $style
            ));
        }
        $this->style = $style;

        return $this;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }
}
