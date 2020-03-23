<?php
namespace App\Helpers;

/**
 * SessionHelper
 */
class SessionHelper
{
    
    /**
     * userData
     *
     * @var mixed
     */
    private $userData;
    
    /**
     * generateCSRFToken
     *
     * @return void
     */
    public function generateCSRFToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_expiration_time'] = strtotime("+15 minutes", strtotime('now'));
        }
    }
    
    /**
     * setUserData
     *
     * @param  mixed $userData
     * @return void
     */
    public function setUserData($userData)
    {
        $_SESSION['userData'] = $userData;
    }

    
    /**
     * tokenExpired
     *
     * @param  mixed $data
     * @return boolean
     */
    public function tokenExpired($data)
    {
        //print_r($data); print_r($_SESSION); echo strtotime('now') ;die;
        if (!isset($data['csrf_token']) || $data['csrf_token'] != $_SESSION['csrf_token'] ||  $_SESSION['csrf_token_expiration_time'] < strtotime('now')) {
            //$this->unsetToken();
            return true;
        } else {
            $this->unsetToken();
            return false;
        }
    }
    
    /**
     * unsetToken
     *
     * @return void
     */
    public function unsetToken()
    {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_expiration_time']);
    }
}
