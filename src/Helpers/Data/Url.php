<?php

namespace BfwForm\Helpers\Data;

/**
 * BFW URL helper
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Url implements DataInterface
{
    /**
     * Validate URL type value
     * 
     * @param string $value : URL to validate
     * @return bool true if valid, false otherwise
     */
    static public function validateUrl(string $value, array $options = [])
    {
        $secure = \BfwForm\Helpers\Secure::secureData($value, 'url', false);
        
        if ($secure === false) {
            return false;
        }
        
        return true;
    }
}