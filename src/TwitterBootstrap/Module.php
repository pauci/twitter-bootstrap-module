<?php

/*
 * @author     pkirschbaum
 * @copyright  Copyright (c) 2012 Pavol Kirschbaum
 * @license    Internal use only
 */

namespace TwitterBootstrap;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'form'                   => __NAMESPACE__ . '\Form\View\Helper\Form',
                'formbutton'             => __NAMESPACE__ . '\Form\View\Helper\FormButton',
                'formelementerrors'      => __NAMESPACE__ . '\Form\View\Helper\FormElementErrors',
                'formelementhelp'        => __NAMESPACE__ . '\Form\View\Helper\FormElementHelp',
                'formelementdescription' => __NAMESPACE__ . '\Form\View\Helper\FormElementHelp',
                'formlayout'             => __NAMESPACE__ . '\Form\View\Helper\FormLayout',
                'formreset'              => __NAMESPACE__ . '\Form\View\Helper\FormReset',
                'formrow'                => __NAMESPACE__ . '\Form\View\Helper\FormRow',
                'formsubmit'             => __NAMESPACE__ . '\Form\View\Helper\FormSubmit',

                'flashmessages'          => __NAMESPACE__ . '\View\Helper\FlashMessages',
            )
        );
    }
}
