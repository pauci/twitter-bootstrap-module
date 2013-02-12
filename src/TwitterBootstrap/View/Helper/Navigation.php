<?php
/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2013 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\View\Helper;

use Zend\View\Helper\Navigation as ZendNavigationHelper;
use Zend\View\Helper\Navigation\PluginManager;

class Navigation extends ZendNavigationHelper
{
    public function getPluginManager()
    {
        if (null === $this->plugins) {
            $pluginManager = new PluginManager();
            $pluginManager->setInvokableClass('menu', 'TwitterBootstrap\View\Helper\Navigation\Menu');
            $this->setPluginManager($pluginManager);
        }
        return $this->plugins;
    }
}
