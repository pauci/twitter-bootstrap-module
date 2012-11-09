<?php

/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormLayout extends AbstractHelper
{
    const LAYOUT_DEFAULT    = 'default';
    const LAYOUT_SEARCH     = 'search';
    const LAYOUT_INLINE     = 'inline';
    const LAYOUT_HORIZONTAL = 'horizontal';

    /**
     * @var string
     */
    protected $layout = self::LAYOUT_DEFAULT;

    /**
     * @param string $layout
     * @return FormLayout
     */
    public function __invoke($layout = null)
    {
        if (null !== $layout) {
            $this->setLayout($layout);
        }
        return $this;
    }

    /**
     * @param string $layout
     * @return FormLayout
     * @throws Exception\InvalidArgumentException
     */
    public function setLayout($layout)
    {
        $layout = strtolower($layout);
        if (!in_array($layout, array(
            self::LAYOUT_DEFAULT,
            self::LAYOUT_SEARCH,
            self::LAYOUT_INLINE,
            self::LAYOUT_HORIZONTAL
        ))) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s excepts either %s::LAYOUT_DEFAULT, %s::LAYOUT_SEARCH, %s::LAYOUT_INLINE or %s::LAYOUT_HORIZONTAL; received "%s"',
                __METHOD__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                (string) $layout
            ));
        }
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
