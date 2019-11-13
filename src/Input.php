<?php

namespace BfwForm;

/**
 * BFW-Form input
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Input {
    
    /**
     * @const ERR_INPUT_REQUIRED : 
     * error code thrown if data obtain process fail on a required input
     */
    const ERR_INPUT_REQUIRED = 201;
    
    /**
     * @const ERR_IMPROPER_VALUE : 
     * error code thrown if value does not match type securing requirement
     */
    const ERR_IMPROPER_VALUE = 202;
    
    /**
     * @const ERR_VALUES_NOMATCH : 
     * error code thrown if current data value does not match former input value
     */
    const ERR_VALUES_NOMATCH = 203;
    
    
    
    /**
     * @var \BfwForm\Options $formOpts : BFW-Form options 
     */
    protected $formOpts;
    
    /**
     * @var Data $data : current input data
     */
    protected $data;
    
    /**
     * @var string $name : input name on the template
     */
    protected $name;
    
    /**
     * @var Input $formerInput : former input form for comparing
     */
    protected $formerInput;
    
    /**
     * @var bool $required : is required or optionnal ?
     */
    protected $required;
    
    /**
     * @var bool $inline : determine if input is block or inline
     */
    protected $inline;
    
    /**
     * @var int $errorCode : current error code
     */
    protected $errorCode;


    /**
     * @var \Scribe\Collection $feedbackCol : input feedback collection
     */
    protected $feedbackCol;




    protected $collection;

    
    /**
     * @var \Aetiom\PhpUtils\Asset $label : label message container
     */
    protected $label = '';
    
    /**
     * @var \Aetiom\PhpUtils\Asset $help : help message container
     */
    protected $help = '';
    
    /**
     * @var \Aetiom\PhpUtils\Asset $placeholder : placeholder message container
     */
    protected $placeholder = '';

    /**
     * @var \Aetiom\PhpUtils\Asset $error : error message container
     */
    protected $error = '';
    
    
    
    /**
     * Get form options
     * @return \BfwForm\Options : form options
     */
    public function getOptions()
    {
        return $this->formOpts;
    }
    
    /**
     * Get Value
     * @return mixed : value of the input form
     */
    public function getValue()
    {
        return $this->data->getValue();
    }
    
    /**
     * Get name
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get former input
     * @return Input former input
     */
    public function getFormerInput()
    {
        return $this->formerInput;
    }
    
    /**
     * Get error code
     * @return int : error code of the input form
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }


    /**
     * Get error code
     * @return \BfwScribe\Collection collection
     */
    public function getCollection()
    {
        return $this->collection;
    }



    public function getLabel():\Aetiom\PhpUtils\Asset
    {
        if (empty($this->label)) {
            return null;
        }
        
        return $this->label;
    }
    
    public function getHelp():\Aetiom\PhpUtils\Asset
    {
        if (empty($this->help)) {
            return null;
        }
        
        return $this->help;
    }
    
    public function getPlaceholder():\Aetiom\PhpUtils\Asset
    {
        if (empty($this->placeholder)) {
            return null;
        }
        
        return $this->placeholder;
    }


    public function getError():\Aetiom\PhpUtils\Asset
    {
        if (empty($this->error)) {
            return null;
        }
        
        return $this->error;
    }

    
    /**
     * Set Value
     * @param mixed $rawValue : raw value to set
     * @return bool true if value is set, false otherwise
     */
    public function setValue($rawValue)
    {
        return $this->data->setValue($rawValue);
    }
    
    /**
     * Set required
     * @param bool $state : true or false
     */
    public function setRequired(bool $state)
    {
        $this->required = $state;
    }
    
    /**
     * Set inline
     * @param bool $state : true or false
     */
    public function setInline($state)
    {
        $this->inline = $state;
    }
    
    /**
     * Link an input form as former input
     * @param \BfwForm\Input $formerInput
     */
    public function link(Input $formerInput) 
    {
        $this->formerInput = $formerInput;
    }
    
    /**
     * Determine if the data is valid or not
     * @return bool true if input is valid, false if input is invalid, null if input is empty
     */
    public function isValid()
    {
        if (empty($this->error)) {
            if (empty($this->getValue())) {
                return null;
            }

            return true;
        }

        return false;
    }
    
    
    
    /**
     * Constructor
     * 
     * @param string               $name    : input name
     * @param array                $param   : input parameters
     * @param \BfwForm\Options $options : BFW-Form options
     */
    public function __construct(string $name, array $param, Options $options) 
    {
        $this->formOpts = $options;
        $this->name = $name;
        
        $this->required = $param['required'] ?? $options->inputRequired;
        $this->inline = $param['inline'] ?? $options->inputInline;
        
        $this->data = new Data($param['data'], $this->formOpts);

        if (!isset($param['locations'])) {
            return;
        }

        if (class_exists('\BfwScribe\Collection')) {
            $this->collection = new \BfwScribe\Collection($name, $param['locations']);
        }
        
        foreach ($param['locations'] as $location => $asset) {
            if (!isset($this->$location)) {
                continue;
            }

            if ($this->collection !== null) {
                $this->$location = $this->collection->getAsset()->select($location);
                continue;
            }
            
            $this->$location = new \Aetiom\PhpUtils\Asset($location);
            $this->$location->update($asset);
        }
    }
    
    
    
    /**
     * Retrieve HTTP request input data and verifying it before storing it
     * @return bool : true in case of success, false in case of error
     */
    public function retrieve()
    {
        /*
        $obtainMethod = $this->formOpts->obtainMethod;
        */

        $accessMethod = $this->formOpts->accessMethod;

        if (strtolower($accessMethod) == 'post') {
            $obtainMethod = ['\BfwForm\Helpers\Http', 'obtainPostKey'];
        } elseif (strtolower($accessMethod) == 'get') {
            $obtainMethod = ['\BfwForm\Helpers\Http', 'obtainGetKey'];
        } else {
            throw new \Exception ('access method '.$accessMethod.' unknown', self::ERR_ACCESS_METHOD_UNKNOWN);
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        try {
            $value = $obtainMethod($this->name, $this->data->getType(), $this->inline);
        } catch (\Exception $e) {
            if ($e->getCode() !== \BFW\Helpers\Secure::ERR_SECURE_ARRAY_KEY_NOT_EXIST) {
                throw $e;
            }

            $value = null;
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        /*
        try {
            $value = $obtainMethod($this->name, $this->inline);
        } catch (\Exception $ex) {
            if ($ex->getCode() !== \BfwForm\Helpers\Http::ERR_KEY_UNKNOWN) {
                throw new \Exception($ex->getCode(), $ex->getMessage());
            }
            
            $value = null;
        }
        */
        
        if (!$this->validateData($value)) {
            return false;
        }
        
        return $this->validateInput();
    }
    


    protected function setError($code)
    {
        $this->errorCode = $code;
        $asset = $code;

        if (isset($this->formOpts->errors[$code])) {
            $asset = $this->formOpts->errors[$code];
        }

        if ($this->collection !== null) {
            $this->error = $this->collection->getAsset()->select('error');

        } else {
            $this->error = new \Aetiom\PhpUtils\Asset('error');
        }
        
        $this->error->update($asset);
    }
    
    
    /**
     * Validate data value
     * @param mixed $value : HTTP request input data value
     * @return bool : true if value is valid, false otherwise
     */
    protected function validateData($value)
    {
        if (empty($value)) {
            if ($this->required === true) {
                $this->setError(self::ERR_INPUT_REQUIRED);
                return false;
            }
            
            $this->data->forceNoValue();
            return true;
        }
        
        if($this->data->setValue($value) !== true) {
            $this->setError(self::ERR_IMPROPER_VALUE);
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate input
     * @return bool true if input is valid, false otherwise
     */
    protected function validateInput()
    {
        if ($this->formerInput === null) {
            return true;
        }
        
        if (!$this->formerInput->isValid()) {
            $this->setError($this->formerInput->getError());
            $this->data->forceNoValue();
            return false;
        }
        
        if ($this->data->getValue() !== $this->formerInput->getValue()) {
            $this->setError(self::ERR_VALUES_NOMATCH);
            return false;
        }
        
        return true;
    }
}