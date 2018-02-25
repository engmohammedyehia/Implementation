<?php
namespace PHPMVC\LIB;

/**
 * Class SessionManager
 * @package PHPMVC\LIB
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class SessionManager extends \SessionHandler
{
    /**
     * @var string the session name constant defined in the app/config/session.php
     */
    private $sessionName = SESSION_NAME;

    /**
     * @var int the session time life constant defined in the app/config/session.php
     */
    private $sessionMaxLifetime = SESSION_LIFE_TIME;

    /**
     * @var bool use SSL when build the cookie
     */
    private $sessionSSL = true;

    /**
     * @var bool make sure the cookie is http only accessible
     */
    private $sessionHTTPOnly = true;

    /**
     * @var string the session path
     */
    private $sessionPath = '/';

    /**
     * @var mixed|string the valid domain name
     */
    private $sessionDomain = '.trivago.test';

    /**
     * @var string the session save path constant defined in the app/config/session.php
     */
    private $sessionSavePath = SESSION_SAVE_PATH;

    /**
     * @var string the cipher algorithm used to encrypt session data
     */
    private $sessionCipherAlgo = 'AES-128-ECB';

    /**
     * @var string the cipher key
     */
    private $sessionCipherKey = 'WYCRYPT0K3Y@2016';

    /**
     * @var int TTL
     */
    private $ttl = 30;

    /**
     * SessionManager constructor.
     */
    public function __construct()
    {

        $this->sessionSSL = isset($_SERVER['HTTPS']) ? true : false;
        $this->sessionDomain = str_replace('www.', '', $_SERVER['SERVER_NAME']);

        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.save_handler', 'files');

        session_name($this->sessionName);

        session_save_path($this->sessionSavePath);

        session_set_cookie_params(
            $this->sessionMaxLifetime, $this->sessionPath,
            $this->sessionDomain, $this->sessionSSL,
            $this->sessionHTTPOnly
        );
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key) {
        if(isset($_SESSION[$key])) {
            $data = @unserialize($_SESSION[$key]);
            if($data === false) {
                return $_SESSION[$key];
            } else {
                return $data;
            }
        } else {
            trigger_error('No session key ' . $key . ' exists', E_USER_NOTICE);
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value) {
        if(is_object($value)) {
            $_SESSION[$key] = serialize($value);
        } else {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($_SESSION[$key]) ? true : false;
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param string $id
     * @return string
     * Implementing the read method for the custom session handler
     */
    public function read($id)
    {
        return openssl_decrypt(parent::read($id), $this->sessionCipherAlgo, $this->sessionCipherKey);
    }

    /**
     * @param string $id
     * @param string $data
     * @return bool
     * Implementing the write method for the custom session handler
     */
    public function write($id, $data)
    {
        return parent::write($id, openssl_encrypt($data, $this->sessionCipherAlgo, $this->sessionCipherKey));
    }

    /**
     * Starts the session
     */
    public function start()
    {
        if('' === session_id()) {
            if(session_start()) {
                $this->setSessionStartTime();
                $this->checkSessionValidity();
            }
        }
    }

    /**
     * @return bool
     * sets the session start time to check its validity
     */
    private function setSessionStartTime()
    {
        if(!isset($this->sessionStartTime)) {
            $this->sessionStartTime = time();
        }
        return true;
    }

    /**
     * @return bool true if the session is valid, false otherwise
     *
     */
    private function checkSessionValidity()
    {
        if((time() - $this->sessionStartTime) > ($this->ttl * 60)) {
            $this->renewSession();
            $this->generateFingerPrint();
        }
        return true;
    }

    /**
     * @return bool true when renewing the session
     */
    private function renewSession()
    {
        $this->sessionStartTime = time();
        return session_regenerate_id(true);
    }

    /**
     * Kills the session
     */
    public function kill()
    {
        session_unset();

        setcookie(
            $this->sessionName, '', time() - 1000,
            $this->sessionPath, $this->sessionDomain,
            $this->sessionSSL, $this->sessionHTTPOnly
        );

        session_destroy();
    }

    /**
     * sets the application language key
     */
    public function setLanguage()
    {
        if(!isset($this->lang)) {
            $this->lang = APP_DEFAULT_LANGUAGE;
        }
    }

    /**
     * Check for a valid finger print to prevent session hijacking
     */
    private function generateFingerPrint()
    {
        $userAgentId = $_SERVER['HTTP_USER_AGENT'];
        $this->cipherKey = openssl_random_pseudo_bytes(16);
        $sessionId = session_id();
        $this->fingerPrint = md5($userAgentId . $this->cipherKey . $sessionId);
    }

    /**
     * @return bool true if the finger print is valid
     */
    public function isValidFingerPrint()
    {
        if(!isset($this->fingerPrint)) {
            $this->generateFingerPrint();
        }

        $fingerPrint = md5($_SERVER['HTTP_USER_AGENT'] . $this->cipherKey . session_id());

        if($fingerPrint === $this->fingerPrint) {
            return true;
        }

        return false;
    }

    /*
     * dumps the session variables
     */

    public function dumpSessionVariables()
    {
        var_dump($_SESSION);
    }

    /**
     * @param int $maxLifetime
     * @return bool|void
     * Garbage Collector
     */
    public function gc($maxLifetime)
    {
        parent::gc($maxLifetime);
    }
}