<?php

namespace BfwForm;

/**
 * BFW-Form token controller
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package BFW-Form
 * @version 1.0
 */
class Token
{
    /**
     * @const int ERR_NO_TOKEN : 
     * error code returned if there HTTP Request does not contain token data
     */
    const ERR_NO_TOKEN = 1022;
    
    /**
     * @const int ERR_NO_MATCH : 
     * error code returned if HTTP Request token does not match Session token
     */
    const ERR_NO_MATCH = 1022;
    
    /**
     * @const int ERR_REVOKED : error code returned if current token is revoked
     */
    const ERR_REVOKED = 1022;
    
    /**
     * @const int ERR_INACTIVITY : 
     * error code returned if current token has expired
     */
    const ERR_INACTIVITY = 1022;
    
    
    
    /**
     * @var \Aetiom\PhpUtils\Session : token session
     */
    protected $session;
    
    /**
     * @var \BfwForm\Input : token input
     */
    protected $input;
    
    /**
     * @var \Aetiom\PhpUtils\Token : token data
     */
    protected $token;
    
    
    
    /**
     * Get token key
     * @return string token key
     */
    public function getKey()
    {
        return $this->token->getKey();
    }
    
    
    
    /**
     * Constructor
     * 
     * @param string $formId : form id
     * @param \BfwForm\Options $options
     */
    public function __construct($formId, Options $options) 
    {   
        $this->options = $options;
        
        $session = new \Aetiom\PhpUtils\Session('bfw-form');
        $this->session = $session->select($formId)->select('token');
        
        $data = $this->session->fetch();
        $this->token = new \Aetiom\PhpUtils\Token($data);
        
        $this->input = new Input($this->options->tokenKey, [
                'data'     => 'string',
                'required' => true
            ], $this->options);
    }
    
    
    
    /**
     * Retrieve token from HTTP request
     * @return bool|int : true if we received the expected token id, error id otherwise
     */
    public function retrieve()
    {
        if ($this->input->retrieve() === false) {
            return self::ERR_NO_TOKEN;
        }
        
        if ($this->input->getValue() !== $this->token->getKey()) {
            return self::ERR_NO_MATCH;
        }
        
        return true;
    }
    
    /**
     * Validate the token retrieved from HTTP request
     * @return boolean
     */
    public function validate()
    {
        $retrieve = $this->retrieve();
        if ($retrieve !== true) {
            return $retrieve;
        }
        
        if ($this->token->isRevoked() === true) {
            return self::ERR_REVOKED;
        }
        if ($this->token->hasExpired() === true) {
            return self::ERR_INACTIVITY;
        }
        
        // revoke current token
        $revokeTime = time();
        
        $this->token->revoke($revokeTime);
        $this->session->select('revoke')->update($revokeTime);
        
        return true;
    }
    
    /**
     * Create and save new token id
     * 
     * @param int $expire : token expire time
     * @return string : token id
     */
    public function create($expire = null)
    {
        if (empty($expire)) {
            $expire = $this->options->tokenExpire;
        }
        
        $tokData = $this->token->create($expire);
        
        $this->session->update($tokData);
        return $this->getKey();
    }
    
    
    
    /**
     * Refresh token, adding expire time to current time
     * @param int $expire : token expire time
     */
    public function refresh($expire = null)
    {
        if (empty($expire)) {
            $expire = $this->options->tokenExpire;
        }
        
        if ($this->token->refresh($expire) === false) {
            return false;
        }
        
        $this->session->update(['expire' => $this->token->getExpiringTime()]);
        return true;
    }
}
