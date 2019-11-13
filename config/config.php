<?php
/**
 * Config file for module bfw-form
 * @author Alexandre Moittié <aetiom@protonmail.com>
 * @package bfw-form
 * @version 1.0
 */


/**
 * Return config
 */
return [
    
    /**
     * @var bool inputRequired : default input required state
     */
    'inputRequired' => false,
    
    /**
     * @var integer $inputPart : default input part number
     */
    'inputPart' => 0,
    
    /**
     * @var bool dataHtmlEnt : default data html entities state
     */
    'dataHtmlEnt' => false,
    
    /**
     * @var bool dataInline : default data inline state
     */
    'dataInline' => true,
    
    /**
     * @var array obtainMethod : static method used in data obtain process
     */
    'obtainMethod' => ['\BfwForm\Helpers\Datas', 'obtainPost'],
    
    /**
     * @var array secureMethod : static method used in data secure process
     */
    'secureMethod' => ['\BfwForm\Helpers\Datas', 'secure'],
    
    /**
     * @var array validMethods : static methods used in data validate process
     */
    'validMethods' => [
        'txt' => ['\BfwForm\Helpers\Datas' => 'validateTxt'],
        'pwd' => ['\BfwForm\Helpers\Datas' => 'validatePwd'],
        'vat' => ['\BfwForm\Helpers\Datas' => 'validateVat'],
        'cid' => ['\BfwForm\Helpers\Datas' => 'validateCid'],

        'reCaptcha'    => ['\BfwForm\Helpers\Datas' => 'validateReCaptcha'],
        'voightKampff' => ['\BfwForm\Helpers\Datas' => 'validateVoightKampff'],
    ],
    
    /**
     * @var array typeList : supported data type
     */
    'typeList' => ['int', 'float', 'bool', 'string',
                'email', 'html', 'url', 'txt',
                'pwd', 'cid', 'vat'],
    
    /**
     * @var array typeShort : data type shorter
     */
    'typeShort' => [
        'integer'  => 'int', 
        'double'   => 'float',     
        'boolean'  => 'bool',
        'text'     => 'txt',
        'password' => 'pwd',
        'passwd'   => 'pwd',
        'corpid'   => 'cid'
    ],
        
    /**
    * @var array errors : form error message collection
    */
    'errors' => [
        
        /**
         * @var array \BfwForm\Input::ERR_IMPROPER_VALUE : 
         * error message for empty captcha answers
         */
        \BfwForm\Input::ERR_IMPROPER_VALUE => [
            'en' => \BfwForm\Input::KEY_CURRENT_INPUT
                                                .' : improper value.',
            'fr' => \BfwForm\Input::KEY_CURRENT_INPUT
                                                .' : valeur non conforme.'
        ],

        /**
         * @var array \BfwForm\Form\Input::ERR_INPUT_REQUIRED : 
         * error message for wrong captcha answers
         */
        \BfwForm\Input::ERR_INPUT_REQUIRED => [
            'en' => \BfwForm\Input::KEY_CURRENT_INPUT.' : required value.',
            'fr' => \BfwForm\Input::KEY_CURRENT_INPUT.' : valeur requise.'
        ],

        /**
         * @var array \BfwForm\Form\Input::ERR_VALUES_NOMATCH : 
         * error message when user get timeouted
         */
        \BfwForm\Input::ERR_VALUES_NOMATCH => [
            'en' => \BfwForm\Input::KEY_CURRENT_INPUT
                    .' : does not match the value of '
                    .\BfwForm\Input::KEY_FORMER_INPUT.'.',
            'fr' => \BfwForm\Input::KEY_CURRENT_INPUT
                    .' : ne correspond pas à la valeur de '
                    .\BfwForm\Input::KEY_FORMER_INPUT.'.'
        ]
        
    ]
];
