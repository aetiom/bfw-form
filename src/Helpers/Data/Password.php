<?php

namespace BfwForm\Helpers\Data;

/**
 * BFW password helper
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Password implements DataInterface
{
    /**
     * @const int ERR_MIN_LEN : error code returned if password is too short
     */
    const ERR_MIN_LEN = 1;
    
    /**
     * @const int ERR_MAX_LEN : error code returned if password is too long
     */
    const ERR_MAX_LEN = 10;
    
    /**
     * @const int ERR_MISSING_CAP : error code returned if password does not
     * match updercase letter requirements
     */
    const ERR_MISSING_CAP = 100;
    
    /**
     * @const int ERR_MISSING_CAP : error code returned if password does not
     * match lowercase letter requirements
     */
    const ERR_MISSING_LOW = 1000;
    
    /**
     * @const int ERR_MISSING_CAP : error code returned if password does not
     * match digit character requirements
     */
    const ERR_MISSING_NUM = 10000;


    /**
     * Validate value for password data type
     * 
     * @param string $value : value to validate
     * @param array  $options : rules to apply with 
     *                        * 'min' as minimum length (dflt: 8)
     *                        * 'max' as maximum length (dflt: 64)
     *                        * 'cap' as min capital letters to use (dflt: 2)
     *                        * 'low' as min lowercase letters to use (dflt: 2)
     *                        * 'num' as min numeric letters to use (dflt: 2)
     * 
     * @return bool|int : true if value is valid, errors compiled code otherwise
     */
    static public function validate(string $value, array $options = []) 
    {
        $opt = array_merge(
                ['min' => 8, 'max' => 64, 'cap' => 2, 'low' => 2, 'num' => 2], 
                $options);
        
        $error = 0;
        
        if (strlen($value) < intval($opt['min'])) {
            $error += self::ERR_MIN_LEN;
        }

        if (strlen($value) > intval($opt['max'])) {
            $error += self::ERR_MAX_LEN;
        }
        
        if(preg_match_all("/[A-Z]/", $value) < intval($opt['cap'])) {
            $error += self::ERR_MISSING_CAP;
        }

        if(preg_match_all("/[a-z]/", $value) < intval($opt['low'])) {
            $error += self::ERR_MISSING_LOW;
        }
        
        if(preg_match_all("/[0-9]/", $value) < intval($opt['num'])) {
            $error += self::ERR_MISSING_NUM;
        }
        
        if ($error !== 0) {
            return $error;
        }
        
        return true;
    }
}