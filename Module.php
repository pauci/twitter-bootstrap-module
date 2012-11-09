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
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'formLayout'        => __NAMESPACE__ . '\Form\View\Helper\FormLayout',
                'form'              => __NAMESPACE__ . '\Form\View\Helper\Form',
                'formLabel'         => __NAMESPACE__ . '\Form\View\Helper\FormLabel',
                'formSubmit'        => __NAMESPACE__ . '\Form\View\Helper\FormSubmit',
                'formReset'         => __NAMESPACE__ . '\Form\View\Helper\FormReset',
                'formButton'        => __NAMESPACE__ . '\Form\View\Helper\FormButton',
                'formElementHelp'   => __NAMESPACE__ . '\Form\View\Helper\FormElementHelp',
                'formElementErrors' => __NAMESPACE__ . '\Form\View\Helper\FormElementErrors',
                'formRow'           => __NAMESPACE__ . '\Form\View\Helper\FormRow',

                'flashMessages'     => __NAMESPACE__ . '\View\Helper\FlashMessages',
            )
        );
    }
}
