<?php

namespace BfwForm\Helpers\Data;

/**
 * BFW text helper
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Text implements DataInterface
{
    /**
     * @const ERR_NO_DIGITS : 
     * error code thrown if there is no digits in 'full digits' text
     */
    const ERR_NO_DIGITS = 2001;
    
    /**
     * @const ERR_DIGITS_NOT_ALLOWED : 
     * error code thrown if there is digits in 'no digits' text
     */
    const ERR_DIGITS_NOT_ALLOWED = 3001;
    
    /**
     * @const ERR_SPACES_NOT_ALLOWED : 
     * error code thrown if there is spaces in 'no spaces' text
     */
    const ERR_SPACES_NOT_ALLOWED = 3010;
    
    /**
     * @const ERR_SPECIALS_NOT_ALLOWED : 
     * error code thrown if there is specials in 'no specials' text
     */
    const ERR_SPECIALS_NOT_ALLOWED = 3100;
    
    
    
    /**
     * Check Text data
     * 
     * @param string $value : data value to secure
     * @param array  $options : validate parameters such as 'fullDigits', 
     *                       'noDigits', 'noSpecials' and 'noSpaces'
     * 
     * @return bool|int : true if text is valid, error code otherwise
     */
    static public function validate(string $value, array $options = [])
    {
        $error = 0;
        if (isset($options['fullDigits']) && $options['fullDigits'] === true
                && preg_match_all("/\D/", $value)) {
            return self::ERR_NO_DIGITS;
        }
        
        if (isset($options['noSpecials']) && $options['noSpecials'] === true
                && preg_match_all("/\W/", $value)) {
            $error += self::ERR_SPECIALS_NOT_ALLOWED;
        }
        
        if (isset($options['noDigits']) && $options['noDigits'] === true
                && preg_match_all("/\d/", $value)) {
            $error += self::ERR_DIGITS_NOT_ALLOWED;
        } 
        
        if (isset($options['noSpaces']) && $options['noSpaces'] === true
                && preg_match_all("/\s/", $value)) {
            $error += self::ERR_SPACES_NOT_ALLOWED;
        }
        
        if ($error !== 0) {
            return $error;
        }
        
        return true;
    }
}