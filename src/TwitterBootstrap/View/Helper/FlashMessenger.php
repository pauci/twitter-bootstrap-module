<?php
/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\View\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger as PluginFlashMessenger;
use Zend\View\Helper\FlashMessenger as ZendFlashMessenger;

class FlashMessenger extends ZendFlashMessenger
{
    /**
     * @var string Templates for the open/close/separators for message tags
     */
    protected $messageCloseString     = '</div>';
    protected $messageOpenFormat      = '<div%s><a href="#" class="close" data-dismiss="alert">&times;</a>';
    protected $messageSeparatorString = PHP_EOL;

    /**
     * @var array Default attributes for the open format tag
     */
    protected $classMessages = array(
        PluginFlashMessenger::NAMESPACE_INFO => 'alert alert-info',
        PluginFlashMessenger::NAMESPACE_ERROR => 'alert alert-error',
        PluginFlashMessenger::NAMESPACE_SUCCESS => 'alert alert-success',
        PluginFlashMessenger::NAMESPACE_DEFAULT => 'alert',
    );

    public function __toString()
    {
        $output = '';
        foreach ($this->classMessages as $namespace => $class) {
            $output .= $this->render($namespace);
        }
        return $output;
    }
}
