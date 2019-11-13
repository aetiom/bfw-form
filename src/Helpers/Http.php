<?php

namespace BfwForm\Helpers;

/**
 * Scribe HTTP helper
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Http extends \BFW\Helpers\Http {
    
    /**
     * Return the class name of the secure helper.
     * Allow to extends the secure helper used by method here
     * 
     * @return string
     */
    protected static function getSecureHelpersName(): string
    {
        return '\BfwForm\Helpers\Secure';
    }

    /**
     * @const ERR_KEY_UNKNOWN : 
     * error code thrown if HTTP request key is unknown (not found)
     */
    const ERR_KEY_UNKNOWN = 9001;
    
    
    
    /**
     * Obtain HTTP request POST data
     * 
     * @param string $key    : POST key to extract
     * @param bool   $inline : inline state, defines trim options
     * 
     * @return mixed POST data
     * @throws \Exception if POST key does not exist
     */
    static public function obtainPost(string $key, bool $inline)
    {
        if (!isset($_POST[$key])) {
            throw new \Exception($key.' post key does not exist', 
                    self::ERR_KEY_UNKNOWN); 
        }
        
        // if not inline, only remove NUL and vertical tab
        if (!$inline) {
            return trim($_POST[$key], ' \0\x0B');
        }
        
        // remove space, tabs (h&v), line feed, carriage return and NUL
        return trim($_POST[$key]);
    }
}
