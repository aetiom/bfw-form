<?php

namespace BfwForm;

/**
 * BFW-Form
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Form {
    
    /**
     * @const ERR_INPUT_ALREADY_EXISTS : 
     * error code thrown if input already exists
     */
    const ERR_INPUT_ALREADY_EXISTS = 301;
    
    /**
     * @const ERR_INPUT_NOT_FOUND : 
     * error code thrown if input does not exist 
     */
    const ERR_INPUT_NOT_FOUND = 302;
    
    
    
    /**
     * @var \BfwForm\Options $options : BFW-Form options
     */
    protected $options;
    
    /**
     * @var string $id : form identifier 
     */
    protected $id;
    
    /**
     * @var \BfwForm\Token $token : BFW-Form token
     */
    protected $token;
    
    /**
     * @var array $inputs : array containing all form \BfwForm\Input inputs
     */
    protected $inputs = [];
    
    /**
     * @var array $missingInputs : array of \BfwForm\Input missing inputs
     */
    protected $missingInputs = [];
    
    /**
     * @var array $invalidInputs : array of \BfwForm\Input invalid inputs
     */
    protected $invalidInputs = [];
    
    
    
    /**
     * Get form options
     * @return \BfwForm\Options : form options
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Get form Id
     * @return type
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get form Id
     * @return type
     */
    public function getToken()
    {
        return $this->token;
    }
    
    /**
     * Get all inputs
     * @return array of \BfwForm\Input inputs
     */
    public function getInputs()
    {
        return $this->inputs;
    }
    
    /**
     * Get input
     * @param string $name : input name
     * 
     * @return \BfwForm\Input input
     * @throws \Exception if input does not exist
     */
    public function getInput($name)
    {
        if (isset($this->inputs[$name])) {
            return $this->inputs[$name];
        }
        
        throw new \Exception('input '.$name.' does not exist', 
                             self::ERR_INPUT_NOT_FOUND);
    }
    
    /**
     * Get missing inputs
     * @return array of \BfwForm\Input missing inputs
     */
    public function getMissingInputs()
    {
        return $this->missingInputs;
    }
    
    /**
     * Get invalid inputs
     * @return array of \BfwForm\Input invalid inputs
     */
    public function getInvalidInputs()
    {
        return $this->invalidInputs;
    }
    
    /**
     * Does the form received an http request ?
     * @return boolean true if http request has been received, false otherwise
     */
    public function hasHttpRequest()
    {
        if ($this->token->retrieve() !== true) {
            return false;
        }
        
        return true;
    }
    
    
    
    /**
     * Constructor
     * @param string $id    : form id
     * @param array  $param : form parameters
     */
    public function __construct(string $id, array $param = [])
    {
        $this->options = new Options($param);
        $this->id = $id;
        
        $this->token = new Token($id, $this->options);
    }

    public function create()
    {
        $this->token->create();
    }
    
    /**
     * Set inputs
     * @param array $map : form map containing input names as key 
     * and input parameters in an array as value 
     */
    public function setInputs(array $map)
    {
        foreach ($map as $name => $param) {
            $this->addInput($name, $param);
        }
    }
    
    /**
     * Add one input
     * @param string $name  : input name
     * @param array  $param : input parameters
     */
    public function addInput(string $name, array $param = [])
    {
        if (isset($this->inputs[$name]) && !empty($this->inputs[$name])){
            throw new \Exception('input '.$name.' already exists', 
                    self::ERR_INPUT_ALREADY_EXISTS);
        }
        
        $this->inputs[$name] = new Input($name, $param, $this->options);
        
        if (isset($param['formerInput']) 
                && isset($this->inputs[$param['formerInput']])) {
            $this->inputs[$name]->link($this->inputs[$param['formerInput']]);
        }
    }
    
    /**
     * Retrieve HTTP request form data for current form
     * @return bool : true in case of success, false otherwise
     */
    public function retrieve()
    {
        if ($this->validHttpRequest() !== true) {
            echo 'token nok';
            exit;

            return false;
        }
        
        $error = false;
        foreach ($this->inputs as $name => $input) {
            if ($input->retrieve()) {
                continue;
            }
            
            $error = true;
            
            if ($input->getError() === Input::ERR_INPUT_REQUIRED) {
                $this->missingInputs[$name] = $input;
                continue;
            }

            $this->invalidInputs[$name] = $input;
        }
        
        /*
        var_dump($this->inputs);
        var_dump($this->invalidInputs);
        var_dump($this->missingInputs);
        exit;
        */

        return !$error;
    }
    
    
    
    /**
     * Validate HTTP Request
     * @return bool|int true if HTTP Request is valid, error code otherwise
     */
    protected function validHttpRequest()
    {
        return $this->token->validate();
    }
}
