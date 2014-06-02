<?php

namespace Backend\Modules\FormBuilder\Ajax;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\FormBuilder\Engine\Helper as FormBuilderHelper;
use Backend\Modules\FormBuilder\Engine\Model as BackendFormBuilderModel;

/**
 * Save a field via ajax.
 *
 * @author Dieter Vanden Eynde <dieter.vandeneynde@wijs.be>
 */
class SaveField extends BackendBaseAJAXAction
{
    /**
     * @var int
     */
    private $formId;
    private $fieldId;

    /**
     * @var string
     */
    private $type;
    private $label;
    private $values;
    private $defaultValues;
    private $required;
    private $requiredErrorMessage;
    private $validation;
    private $validationParameter;
    private $errorMessage;
    private $replyTo;

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->getData();

        // check if we have enough parameters
        $inputError = $this->validateInput();
        if (!empty($inputError)) {
            return $this->output(self::BAD_REQUEST, null, $inputError);
        }

        if ($this->type != 'textbox') {
            // extra validation is only possible for textfields
            $this->validation = '';
            $this->validationParameter = '';
            $this->errorMessage = '';
        }

        // validate needed parameters for the type of field we're saving
        $dataErrors = $this->validateData();
        if (!empty($dataErrors)) {
            $this->output(self::OK, array('errors' => $dataErrors), 'form contains errors');
        }

        // clean up our input
        $this->cleanUpInput();
        $fieldHTML = $this->saveField();

        // success output
        $this->output(
            self::OK,
            array(
                'field_id' => $this->fieldId,
                'field_html' => $fieldHTML,
            ),
            'field saved'
        );
    }

    /**
     * Fetches the data from the ajax request
     */
    private function getData()
    {
        // get all parameters from the $_POST array
        $this->formId = \SpoonFilter::getPostValue('form_id', null, '', 'int');
        $this->fieldId = \SpoonFilter::getPostValue('field_id', null, '', 'int');
        $this->type = \SpoonFilter::getPostValue(
            'type',
            array('checkbox', 'dropdown', 'heading', 'paragraph', 'radiobutton', 'submit', 'textarea', 'textbox'),
            '',
            'string'
        );
        $this->label = trim(\SpoonFilter::getPostValue('label', null, '', 'string'));
        $this->values = trim(\SpoonFilter::getPostValue('values', null, '', 'string'));
        $this->defaultValues = trim(\SpoonFilter::getPostValue('default_values', null, '', 'string'));
        $this->required = \SpoonFilter::getPostValue('required', array('Y','N'), 'N', 'string');
        $this->requiredErrorMessage = trim(\SpoonFilter::getPostValue('required_error_message', null, '', 'string'));
        $this->validation = \SpoonFilter::getPostValue('validation', array('email', 'numeric'), '', 'string');
        $this->validationParameter = trim(\SpoonFilter::getPostValue('validation_parameter', null, '', 'string'));
        $this->errorMessage = trim(\SpoonFilter::getPostValue('error_message', null, '', 'string'));
        $this->replyTo = \SpoonFilter::getPostValue('reply_to', array('Y','N'), 'N', 'string');
    }

    /**
     * Validates the fields we got trough ajax
     *
     * @return null|string
     */
    private function validateInput()
    {
        if (!BackendFormBuilderModel::exists($this->formId)) {
            return 'form does not exist';
        } elseif ($this->fieldId !== 0 && !BackendFormBuilderModel::existsField($this->fieldId, $this->formId)) {
            return 'field does not exist';
        } elseif ($this->type == '') {
            return 'invalid type provided';
        }

        return null;
    }

    /**
     * Validates the data we got trough ajax
     *
     * @return array
     */
    private function validateData()
    {
        $errors = array();

        // validate textbox
        switch ($this->type) {
            case 'textbox':
            case 'textarea':
                if ($this->label == '') {
                    $errors['label'] = BL::getError('LabelIsRequired');
                }
                if ($this->required == 'Y' && $this->requiredErrorMessage == '') {
                    $errors['required_error_message'] = BL::getError('ErrorMessageIsRequired');
                }
                if ($this->validation != '' && $this->errorMessage == '') {
                    $errors['error_message'] = BL::getError('ErrorMessageIsRequired');
                }
                break;
            case 'heading':
            case 'paragraph':
            case 'submit':
                if ($this->values == '') {
                    $errors['values'] = BL::getError('ValueIsRequired');
                }
                break;
            case 'dropdown':
                $this->values = trim($this->values, ',');

                if ($this->label == '') {
                    $errors['label'] = BL::getError('LabelIsRequired');
                }
                if ($this->required == 'Y' && $this->requiredErrorMessage == '') {
                    $errors['required_error_message'] = BL::getError('ErrorMessageIsRequired');
                }
                if ($this->values == '') {
                    $errors['values'] = BL::getError('ValueIsRequired');
                }
                break;
            case 'radiobutton':
                if ($this->label == '') {
                    $errors['label'] = BL::getError('LabelIsRequired');
                }
                if ($this->required == 'Y' && $this->requiredErrorMessage == '') {
                    $errors['required_error_message'] = BL::getError('ErrorMessageIsRequired');
                }
                if ($this->values == '') {
                    $errors['values'] = BL::getError('ValueIsRequired');
                }
                break;
            case 'checkbox':
                if ($this->label == '') {
                    $errors['label'] = BL::getError('LabelIsRequired');
                }
                if ($this->required == 'Y' && $this->requiredErrorMessage == '') {
                    $errors['required_error_message'] = BL::getError('ErrorMessageIsRequired');
                }
                break;
        }

        return $errors;
    }

    /**
     * Cleans up the input
     */
    private function cleanUpInput()
    {
        // htmlspecialchars except for paragraphs
        if ($this->type != 'paragraph') {
            if ($this->values != '') {
                $this->values = \SpoonFilter::htmlspecialchars($this->values);
            }
            if ($this->defaultValues != '') {
                $this->defaultValues = \SpoonFilter::htmlspecialchars($this->defaultValues);
            }
        }

        // split
        if ($this->type == 'dropdown' || $this->type == 'radiobutton' || $this->type == 'checkbox') {
            $this->values = (array) explode('|', $this->values);
        }
    }

    /**
     * Saves the field
     *
     * @return string The rendered HTML for the saved field
     */
    private function saveField()
    {
        // settings
        $settings = array();
        if ($this->label != '') {
            $settings['label'] = \SpoonFilter::htmlspecialchars($this->label);
        }
        if ($this->values != '') {
            $settings['values'] = $this->values;
        }
        if ($this->defaultValues != '') {
            $settings['default_values'] = $this->defaultValues;
        }

        // reply-to, only for textboxes
        if ($this->type == 'textbox') {
            $settings['reply_to'] = ($this->replyTo == 'Y');
        }

        // build array
        $field = array(
            'form_id' => $this->formId,
            'type' => $this->type,
            'settings' => (!empty($settings) ? serialize($settings) : null),
        );

        // update existing fields, insert new fields
        if ($this->fieldId !== 0) {
            BackendFormBuilderModel::updateField($this->fieldId, $field);
            BackendFormBuilderModel::deleteFieldValidation($this->fieldId);
        } else {
            $field['sequence'] = BackendFormBuilderModel::getMaximumSequence($this->formId) + 1;
            $this->fieldId = BackendFormBuilderModel::insertField($field);
        }

        $this->saveValidation();

        // submit button isnt parsed but handled directly via javascript
        if ($this->type == 'submit') {
            return '';
        }

        // get item from database (i do this call again to keep the points of failure as low as possible)
        $field = BackendFormBuilderModel::getField($this->fieldId);

        // return the parsed version of the field.
        return FormBuilderHelper::parseField($field);
    }

    private function saveValidation()
    {
        // save validation for required fields
        if ($this->required == 'Y') {
            BackendFormBuilderModel::insertFieldValidation(
                array(
                    'field_id' => $this->fieldId,
                    'type' => 'required',
                    'error_message' => \SpoonFilter::htmlspecialchars($this->requiredErrorMessage),
                )
            );
        }

        // other types of validation
        if ($this->validation != '') {
            BackendFormBuilderModel::insertFieldValidation(
                array(
                    'field_id' => $this->fieldId,
                    'type' => $this->validation,
                    'error_message' => \SpoonFilter::htmlspecialchars($this->errorMessage),
                    'parameter' => ($this->validationParameter != '') ?
                        \SpoonFilter::htmlspecialchars($this->validationParameter) :
                        null,
                )
            );
        }
    }
}
