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
     * @var bool dataHashed : default data hashed state
     */
    'dataHashed' => false,

    /**
     * @var array accessMethod : default access method used to get form data
     */
    'accessMethod' => 'POST',
    
    /**
     * @var array dataClasses : data classes that implements 
     * \BfwForm\Helpers\Data\DataInterface used for data validate process
     */
    'dataClasses' => [
        'txt'      => '\BfwForm\Helpers\Data\Text',
        'text'     => '\BfwForm\Helpers\Data\Text',
        'pwd'      => '\BfwForm\Helpers\Data\Password',
        'password' => '\BfwForm\Helpers\Data\Password'
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
            'en' => 'Improper value.',
            'fr' => 'Valeur non conforme.'
        ],

        /**
         * @var array \BfwForm\Input::ERR_INPUT_REQUIRED : 
         * error message for wrong captcha answers
         */
        \BfwForm\Input::ERR_INPUT_REQUIRED => [
            'en' => 'Can\'t be empty.',
            'fr' => 'Ne peut pas être vide.'
        ],

        /**
         * @var array \BfwForm\Input::ERR_VALUES_NOMATCH : 
         * error message when user get timeouted
         */
        \BfwForm\Input::ERR_VALUES_NOMATCH => [
            'en' => 'Does not match the previous value',
            'fr' => 'Ne correspond pas à la valeur précédente'
        ]
        
    ]
];
