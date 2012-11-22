<?php
/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View;

class FlashMessages extends AbstractHelper implements
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var FlashMessenger
     */
    protected $flashMessenger;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return FlashMessages
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param FlashMessenger $flashMessenger
     * @return FlashMessages
     */
    public function setFlashMessenger(FlashMessenger $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
        return $this;
    }

    /**
     * @return FlashMessenger
     */
    public function getFlashMessenger()
    {
        if (null === $this->flashMessenger) {
            $sl = $this->getServiceLocator();
            if ($sl instanceof View\HelperPluginManager) {
                $sl = $sl->getServiceLocator();
            }
            $pluginManager        = $sl->get('ControllerPluginManager');
            $this->flashMessenger = $pluginManager->get('flashMessenger');
        }
        return $this->flashMessenger;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $flashMessenger = $this->getFlashMessenger();

        if (!$flashMessenger->hasMessages()) {
            return '';
        }

        $markup = '';

        $escaper = $this->view->plugin('escapeHtml');
        foreach ($flashMessenger->getMessages() as $message) {
            $markup .= '<div class="alert alert-success">' . "\r\n";
            $markup .= '    <button type="button" class="close" data-dismiss="alert">×</button>' . "\r\n";
            $markup .= '    ' . $escaper($message);
            $markup .= "</div>\r\n";
        }

        return $markup;
    }
}
