<?php

namespace BfwForm\Helpers;

/**
 * Scribe data helper
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Data extends BFW\Helpers\Datas{
    
    /**
     * @const int ERR_PWD_MIN_LEN : error code returned if password is too short
     */
    const ERR_PWD_MIN_LEN = 1;
    
    /**
     * @const int ERR_PWD_MAX_LEN : error code returned if password is too long
     */
    const ERR_PWD_MAX_LEN = 10;
    
    /**
     * @const int ERR_PWD_MISSING_CAP : error code returned if password does not
     * match updercase letter requirements
     */
    const ERR_PWD_MISSING_CAP = 100;
    
    /**
     * @const int ERR_PWD_MISSING_CAP : error code returned if password does not
     * match lowercase letter requirements
     */
    const ERR_PWD_MISSING_LOW = 1000;
    
    /**
     * @const int ERR_PWD_MISSING_CAP : error code returned if password does not
     * match digit character requirements
     */
    const ERR_PWD_MISSING_NUM = 10000;


    /**
     * @const ERR_TXT_NO_DIGITS : 
     * error code thrown if there is no digits in 'full digits' text
     */
    const ERR_TXT_NO_DIGITS = 2001;
    
    /**
     * @const ERR_TXT_DIGITS_NOT_ALLOWED : 
     * error code thrown if there is digits in 'no digits' text
     */
    const ERR_TXT_DIGITS_NOT_ALLOWED = 3001;
    
    /**
     * @const ERR_TXT_SPACES_NOT_ALLOWED : 
     * error code thrown if there is spaces in 'no spaces' text
     */
    const ERR_TXT_SPACES_NOT_ALLOWED = 3010;
    
    /**
     * @const ERR_TXT_SPECIALS_NOT_ALLOWED : 
     * error code thrown if there is specials in 'no specials' text
     */
    const ERR_TXT_SPECIALS_NOT_ALLOWED = 3100;
    
    
    
    /**
     * Check Text data
     * 
     * @param string $value : data value to secure
     * @param array  $param : validate parameters such as 'fullDigits', 
     *                       'noDigits', 'noSpecials' and 'noSpaces'
     * 
     * @return bool|int : true if text is valid, error code otherwise
     */
    static public function validateText($value, $param = [])
    {
        $error = 0;
        if (isset($param['fullDigits']) && $param['fullDigits'] === true
                && preg_match_all("/\D/", $value)) {
            return self::ERR_NO_DIGITS;
        }
        
        if (isset($param['noSpecials']) && $param['noSpecials'] === true
                && preg_match_all("/\W/", $value)) {
            $error += self::ERR_SPECIALS_NOT_ALLOWED;
        }
        
        if (isset($param['noDigits']) && $param['noDigits'] === true
                && preg_match_all("/\d/", $value)) {
            $error += self::ERR_DIGITS_NOT_ALLOWED;
        } 
        
        if (isset($param['noSpaces']) && $param['noSpaces'] === true
                && preg_match_all("/\s/", $value)) {
            $error += self::ERR_SPACES_NOT_ALLOWED;
        }
        
        if ($error !== 0) {
            return $error;
        }
        
        return true;
    }
    
    
    
    /**
     * Validate value for password data type
     * 
     * @param string $value : value to validate
     * @param array  $rules : rules to apply with 
     *                        * 'min' as minimum length (dflt: 8)
     *                        * 'max' as maximum length (dflt: 64)
     *                        * 'cap' as min capital letters to use (dflt: 2)
     *                        * 'low' as min lowercase letters to use (dflt: 2)
     *                        * 'num' as min numeric letters to use (dflt: 2)
     * 
     * @return bool|int : true if value is valid, errors compiled code otherwise
     */
    static public function validatePwd(string $value, array $rules = []) 
    {
        $rul = array_merge(
                ['min' => 8, 'max' => 64, 'cap' => 2, 'low' => 2, 'num' => 2], 
                $rules);
        
        $error = 0;
        
        if (strlen($value) < intval($rul['min'])) {
            $error += self::ERR_PWD_MIN_LEN;
        }

        if (strlen($value) > intval($rul['max'])) {
            $error += self::ERR_PWD_MAX_LEN;
        }
        
        if(preg_match_all("/[A-Z]/", $value) < intval($rul['cap'])) {
            $error += self::ERR_PWD_MISSING_CAP;
        }

        if(preg_match_all("/[a-z]/", $value) < intval($rul['low'])) {
            $error += self::ERR_PWD_MISSING_LOW;
        }
        
        if(preg_match_all("/[0-9]/", $value) < intval($rul['num'])) {
            $error += self::ERR_PWD_MISSING_NUM;
        }
        
        if ($error !== 0) {
            return $error;
        }
        
        return true;
    }
    
    /**
     * Validate email type value
     * 
     * @param string $value : email to validate
     * @return bool true if valid, false otherwise
     */
    static public function validateEmail(string $value)
    {
        $secure = Secure::secureData($value, 'email', false);
        
        if ($secure === false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate URL type value
     * 
     * @param string $value : URL to validate
     * @return bool true if valid, false otherwise
     */
    static public function validateUrl(string $value)
    {
        $secure = Secure::secureData($value, 'url', false);
        
        if ($secure === false) {
            return false;
        }
        
        return true;
    }
}
