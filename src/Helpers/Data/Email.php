<?php

namespace BfwForm\Helpers\Data;

/**
 * BFW email helper
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Email implements DataInterface
{
    /**
     * Validate email type value
     * 
     * @param string $value : email to validate
     * @return bool true if valid, false otherwise
     */
    static public function validate(string $value, array $options = [])
    {
        $secure = \BfwForm\Helpers\Secure::secureData($value, 'email', false);
        
        if ($secure === false) {
            return false;
        }
        
        return true;
    }
}