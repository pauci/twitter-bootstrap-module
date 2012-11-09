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
use Zend\Form\View\Helper\FormElementErrors;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 */
class FormRow extends BaseFormRow
{
    const LABEL_DEFAULT = 'default';

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

        if (!empty($label)) {
            $label = $escapeHtmlHelper($label);
            $labelAttributes = $element->getLabelAttributes();

            if (empty($labelAttributes)) {
                $labelAttributes = $this->labelAttributes;
            }

            $type          = $element->getAttribute('type');
            $labelPosition = $this->getLabelPosition();

            if (self::LABEL_DEFAULT == $labelPosition) {
                $labelPosition = in_array($type, array('checkbox', 'radio'))
                    ? self::LABEL_APPEND
                    : self::LABEL_PREPEND;
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
                        switch ($this->formStyle) {
                            case self::STYLE_DEFAULT:
                            default:
                                $markup = $labelOpen . $label . $labelClose . ' ' . $elementString . $elementErrors;
                                break;

                            case self::STYLE_HORIZONTAL:
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
                        switch ($this->formStyle) {
                            case self::STYLE_DEFAULT:
                            default:
                                $markup = $labelOpen . $elementString . ' ' . $label . $labelClose . $elementErrors;
                                break;

                            case self::STYLE_HORIZONTAL:
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
            $markup = $elementString . $elementErrors;
        }

        return $markup;
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param null|ElementInterface $element
     * @param null|string $formStyle
     * @param null|string $labelPosition
     * @return string|FormRow
     */
    public function __invoke(ElementInterface $element = null, $formStyle = null, $labelPosition = null)
    {
        if (!$element) {
            return $this;
        }

        if ($formStyle !== null) {
            $this->setFormStyle($formStyle);
        }

        return parent::__invoke($element, $labelPosition);
    }

    /**
     * @param string $formStyle
     * @return FormRow
     * @throws Exception\InvalidArgumentException
     */
    public function setFormStyle($formStyle)
    {
        $formStyle = strtolower($formStyle);
        if (!in_array($formStyle, array(
            self::STYLE_DEFAULT,
            self::STYLE_SEARCH,
            self::STYLE_INLINE,
            self::STYLE_HORIZONTAL
        ))) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s excepts either %s::STYLE_DEFAULT, %s::STYLE_SEARCH, %s::STYLE_INLINE or %s::STYLE_HORIZONTAL; received "%s"',
                __METHOD__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                __CLASS__,
                (string) $formStyle
            ));
        }
        $this->formStyle = $formStyle;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormStyle()
    {
        return $this->formStyle;
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
     * @return FormElementErrors
     */
    protected function getElementErrorsHelper()
    {
        if ($this->elementErrorsHelper) {
            return $this->elementErrorsHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->elementErrorsHelper = $this->view->plugin('form_element_errors');
        }

        if (!$this->elementErrorsHelper instanceof FormElementErrors) {
            $this->elementErrorsHelper = new FormElementErrors();
        }

        return $this->elementErrorsHelper;
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
}
