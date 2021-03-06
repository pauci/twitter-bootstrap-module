<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace TwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormRow as BaseFormRow;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 */
class FormRow extends BaseFormRow
{
    const LABEL_DEFAULT = 'default';

    /**
     * @var FormLayout
     */
    protected $formLayoutHelper;

    /**
     * @var string|null
     */
    protected $labelPosition = self::LABEL_DEFAULT;

    /**
     * @var FormElementHelp
     */
    protected $elementHelpHelper;

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @param ElementInterface $element
     * @return string
     * @throws \Zend\Form\Exception\DomainException
     */
    public function render(ElementInterface $element)
    {
        $escapeHtmlHelper    = $this->getEscapeHtmlHelper();
        $labelHelper         = $this->getLabelHelper();
        $elementHelper       = $this->getElementHelper();
        $elementHelpHelper   = $this->getElementHelpHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();
        $label               = $element->getLabel();
        $elementString       = $elementHelper->render($element);
        $elementErrors       = $elementErrorsHelper->render($element);
        $elementErrors      .= $elementHelpHelper->render($element);

        $layout = $this->getLayout();

        if (!empty($label)) {
            $label = $escapeHtmlHelper($label);
            $labelAttributes = $element->getLabelAttributes();

            if (empty($labelAttributes)) {
                $labelAttributes = $this->labelAttributes;
            }

            $type          = $element->getAttribute('type');
            $labelPosition = $this->getLabelPosition();

            if (self::LABEL_DEFAULT == $labelPosition) {
                $addClass = function(&$attributes, $class) {
                    $attributes['class'] = isset($attributes['class'])
                        ? $attributes['class'] . ' ' . $class
                        : $class;
                };
                if (in_array($type, array('checkbox', 'radio'))) {
                    $labelPosition = self::LABEL_APPEND;
                    $addClass($labelAttributes, $type);
                    $element->setLabelAttributes($labelAttributes);
                } else {
                    $labelPosition = self::LABEL_PREPEND;
                    if (FormLayout::LAYOUT_HORIZONTAL == $layout) {
                        $addClass($labelAttributes, 'control-label');
                        $element->setLabelAttributes($labelAttributes);
                    }
                }
            }

            // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
            // labels. The semantic way is to group them inside a fieldset
            if ($type === 'multi_checkbox' || $type === 'multicheckbox' || $type === 'radio') {
                $markup = sprintf(
                    '<fieldset><legend>%s</legend>%s</fieldset>',
                    $label,
                    $elementString);
            } else {
                if ($element->hasAttribute('id')) {
                    $labelOpen = $labelHelper($element);
                    $labelClose = '';
                    $label = '';
                } else {
                    $labelOpen  = $labelHelper->openTag($labelAttributes);
                    $labelClose = $labelHelper->closeTag();
                }

                switch ($labelPosition) {
                    case self::LABEL_PREPEND:
                        switch ($layout) {
                            case FormLayout::LAYOUT_DEFAULT:
                            default:
                                $markup = $labelOpen . $label . $labelClose . ' ' . $elementString . $elementErrors;
                                break;

                            case FormLayout::LAYOUT_HORIZONTAL:
                                $addClass = '';
                                if ($element->getMessages()) {
                                    $addClass = ' error';
                                }
                                $markup = '<div class="control-group' . $addClass . '">'
                                        . $labelOpen . $label . $labelClose
                                        . '<div class="controls">'
                                            . $elementString . $elementErrors
                                        . '</div>'
                                    . '</div>';
                                break;
                        }
                        break;

                    case self::LABEL_APPEND:
                    default:
                        switch ($layout) {
                            case FormLayout::LAYOUT_DEFAULT:
                            default:
                                $markup = $labelOpen . $elementString . ' ' . $label . $labelClose . $elementErrors;
                                break;

                            case FormLayout::LAYOUT_HORIZONTAL:
                                $addClass = '';
                                if ($element->getMessages()) {
                                    $addClass = ' error';
                                }
                                $markup = '<div class="control-group' . $addClass . '">'
                                            . '<div class="controls">'
                                                . $labelOpen . $elementString
                                                . ' ' . $label . $labelClose . $elementErrors
                                            . '</div>'
                                        . '</div>';
                                break;
                        }
                        break;
                }
            }
        } else {
            switch ($layout) {
                case FormLayout::LAYOUT_HORIZONTAL:
                    $addClass = '';
                    if ($element->getMessages()) {
                        $addClass = ' error';
                    }
                    $markup = '<div class="control-group' . $addClass . '">'
                                . '<div class="controls">'
                                    . $elementString . $elementErrors
                                . '</div>'
                            . '</div>';
                    break;
                default:
                    $markup = $elementString . $elementErrors;
                    break;
            }
        }

        return $markup;
    }

    /**
     * Set the label position
     *
     * @param $labelPosition
     * @return FormRow
     * @throws \Zend\Form\Exception\InvalidArgumentException
     */
    public function setLabelPosition($labelPosition)
    {
        $labelPosition = strtolower($labelPosition);
        if (!in_array($labelPosition, array(self::LABEL_DEFAULT, self::LABEL_APPEND, self::LABEL_PREPEND))) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects either %s::LABEL_DEFAULT, %s::LABEL_APPEND or %s::LABEL_PREPEND; received "%s"',
                __METHOD__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                (string) $labelPosition
            ));
        }
        $this->labelPosition = $labelPosition;

        return $this;
    }

    /**
     * Retrieve the FormElementErrors helper
     *
     * @return FormElementHelp
     */
    protected function getElementHelpHelper()
    {
        if ($this->elementHelpHelper) {
            return $this->elementHelpHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->elementHelpHelper = $this->view->plugin('form_element_help');
        }

        if (!$this->elementHelpHelper instanceof FormElementHelp) {
            $this->elementHelpHelper = new FormElementHelp();
        }

        return $this->elementHelpHelper;
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
