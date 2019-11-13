<?php

namespace BfwForm;

/**
 * BFW-Form options
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Options extends \Aetiom\PhpUtils\Options {
    
    /**
     * @var string $defaultLang : default language used for error messages
     */
    public $defaultLang = 'en';
    
    /**
     * @var string $tokenKey : token http request key
     */
    public $tokenKey = 'token';
    
    /**
     * @var int $tokenExpire : token expire time in seconds
     */
    public $tokenExpire = 900;
    
    /**
     * @var bool $inputRequired : default input required state
     */
    public $inputRequired = false;
    
    /**
     * @var bool $inputInline : default data inline state
     */
    public $inputInline = true;
    
    /**
     * @var bool $dataHtmlEnt : default data html entities state
     */
    public $dataHtmlEnt = false;
    
    /**
     * @var bool dataHashed : default data hashed state
     */
    public $dataHashed = false;

    /**
     * @var array accessMethod : default access method used to get form data
     */
    public $accessMethod = 'POST';

    /**
     * @var array $validMethods : static methods used in data validate process
     */
    public $validMethods = [
        'txt'      => ['\BfwForm\Helpers\Data', 'validateText'],
        'text'     => ['\BfwForm\Helpers\Data', 'validateText'],
        'pwd'      => ['\BfwForm\Helpers\Data', 'validatePassword'],
        'password' => ['\BfwForm\Helpers\Data', 'validatePassword']
    ];

    /**
     * @var array $errors : input error codes and messages
     */
    public $errors = [
        /**
         * @var array \BfwForm\Input::ERR_IMPROPER_VALUE : 
         * error message for empty captcha answers
         */
        \BfwForm\Input::ERR_IMPROPER_VALUE => 'Improper value',

        /**
         * @var array \BfwForm\Input::ERR_INPUT_REQUIRED : 
         * error message for wrong captcha answers
         */
        \BfwForm\Input::ERR_INPUT_REQUIRED => 'Can\'t be empty',
        
        /**
         * @var array \BfwForm\Input::ERR_VALUES_NOMATCH : 
         * error message when user get timeouted
         */
        \BfwForm\Input::ERR_VALUES_NOMATCH => 'Does not match the previous value'
    ];
}