<?php

/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as BaseFormElementErrors;

class FormElementErrors extends BaseFormElementErrors
{
    const STYLE_INLINE = 'inline';
    const STYLE_BLOCK  = 'block';

    protected $style = self::STYLE_BLOCK;

    protected $messageCloseString     = '</span>';
    protected $messageOpenFormat      = '<span class="help-%s">';
    protected $messageSeparatorString = '</span><span class="help-%s">';

    /**
     * @return string
     */
    public function getMessageOpenFormat()
    {
        return sprintf($this->messageOpenFormat, $this->style);
    }

    /**
     * @return string
     */
    public function getMessageSeparatorString()
    {
        return sprintf($this->messageSeparatorString, $this->style);
    }

    /**
     * @param string $style
     * @return FormElementErrors
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
