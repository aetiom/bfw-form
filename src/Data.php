<?php

namespace BfwForm;

/**
 * BFW-Form input data
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Data {
    
    /**
     * @const ERR_UNKNOWN_TYPE : 
     * error code thrown if data type is unknown (not supported)
     */
    //const ERR_UNKNOWN_TYPE = 101;
    
    /**
     * @const ERR_CHECK_MISSING_METHOD : 
     * error code thrown if validate method is missing for current data type
     */
    //const ERR_CHECK_MISSING_METHOD = 103;
    
    
    
    /**
     * @var \BfwForm\Options $formOpts : BFW-Form options 
     */
    protected $formOpts;
    
    /**
     * @var string $type : valid data type 
     */
    protected $type;
    
    /**
     * @var bool $htmlEnt : determine usage of htmlentities
     */
    protected $htmlEnt;

    /**
     * @var bool $hashed : determine if value need to be hashed
     */
    protected $hashed;
    
    /**
     * @var array $validate : validate method parameters 
     */
    protected $validate;
    
    /**
     * @var mixed $value : data value 
     */
    protected $value;
    
    
    
    /**
     * Get form options
     * @return \BfwForm\Options : form options
     */
    public function getOptions()
    {
        return $this->formOpts;
    }
    
    /**
     * Get type
     * @return mixed data type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get html entities param
     * @return mixed data htmlEnt
     */
    public function getHtmlEnt()
    {
        return $this->htmlEnt;
    }

    /**
     * Get hashed param
     * @return mixed data hashed
     */
    public function getHashed()
    {
        return $this->hashed;
    }

    /**
     * Get validate method
     * @return mixed data validate method
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Get value
     * @return mixed data value
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set value
     * 
     * @param mixed $rawValue : raw data value
     * @return mixed : true if value is secure and valid, false otherwise
     */
    public function setValue($rawValue)
    {
        /*
        if ($this->type!=='string') {
            var_dump($rawValue);
            exit;
        }
        */

        if ($this->validateData($rawValue) === false) {
            return false;
        }
        
        if ($this->hashed === true) {
            $this->value = \BFW\Helpers\Secure::hash($rawValue);    
        } else {
            $this->value = $rawValue;
        }
        
        /*$secure = $this->secureData($rawValue);
        if ($secure === false) {
            return false;
        }
        
        // SESSION PUSH
        $this->value = $secure;
        */
        return true;
    }
    
    /**
     * Set html entities
     * @param bool $state : true or false
     */
    public function setHtmlEnt($state)
    {
        $this->htmlEnt = $state;
    }

    /**
     * Set hashed
     * @param bool $state : true or false
     */
    public function setHashed($state)
    {
        $this->hashed = $state;
    }
    
    /**
     * Set validate parameters
     * @param array $param : validate method parameters
     */
    public function setValidateParam($param)
    {
        $this->validate = $param;
    }
    
    
    
    /**
     * Constructor
     * 
     * @param string|array         $param   : data parameters
     * @param \BfwForm\Options $options : BFW-Form options
     */
    public function __construct($param, Options $options) 
    {   
        $this->formOpts = $options;
        
        $this->type = $param;
        if (is_array($param)) {
            $this->type = $param['type'];    
        }
        
        $this->htmlEnt = $param['htmlEnt'] ?? $options->dataHtmlEnt;
        $this->validate = $param['validate'] ?? null;

        if ($this->type === 'pwd' || $this->type === 'password') {
            $this->hashed = true;
        } else {
            $this->hashed = $param['hashed'] ?? $options->dataHashed;
        }
        
        if(isset($param['value']) && !empty($param['value'])) {
            $this->setValue($param['value']);

        } else {
            $this->forceNoValue();
        }
    }
    
    /**
     * Force no value
     * Set value to "no value" default standards based on data type
     */
    public function forceNoValue()
    {
        $val = '';
        if ($this->type === 'bool' || $this->type === 'boolean' ) {
            $val = false;
        } elseif (
                $this->type === 'int'   || $this->type === 'integer' || 
                $this->type === 'float' || $this->type === 'double'
            ) {
            $val = 0;
        }
        
        $this->value = $val;
    }
    
    
    
    /**
     * Secure value
     * @return bool true if value is secure, false otherwise
     */
    /*
    protected function secureData($data)
    {
        $secureMethod = $this->formOpts->secureMethod;
        $value = $secureMethod($data, $this->type, $this->htmlEnt);
        
        if ($value === false && ($this->type != 'bool')) {
            return false;
        }
        
        return $value;
    }
    */

    /**
     * Check value
     * 
     * @return mixed : true if data is valid, false otherwise
     */
    protected function validateData($data) 
    {
        if (!isset($this->formOpts->validMethods[$this->type])) {
            return true;
        }
        
        $validateMethod = $this->formOpts->validMethods[$this->type];
        
        /*
        var_dump($validateMethod);
        var_dump($validateMethod($data, $this->validate));
        exit;
        */

        if ($validateMethod($data, $this->validate) !== true) {

            return false;
        }
        
        return true;
    }
    
    
    
    /**
     * Format type
     * 
     * @param string $type : data type
     * @return string formated type
     * 
     * @throws \Exception if type is unknown (not supported)
     */
    /*
    private function formatType($type) 
    {
        if (isset($this->formOpts->typeShort[$type])) {
            $type = $this->formOpts->typeShort[$type];
        }
        
        if (array_search($type, $this->formOpts->typeList) === false) {
            throw new \Exception(
                    $type.' is not a valid data type',
                    self::ERR_UNKNOWN_TYPE);
        }
        
        return $type;
    }
    */
}
