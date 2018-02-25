<?php
namespace PHPMVC\lib;

/**
 * Class Messenger
 * @package PHPMVC\lib
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class Messenger
{
    /**
     * Success message
     */
    const APP_MESSAGE_SUCCESS       = 1;

    /**
     * Error message
     */
    const APP_MESSAGE_ERROR         = 2;

    /**
     * Warning message
     */
    const APP_MESSAGE_WARNING       = 3;

    /**
     * Information message
     */
    const APP_MESSAGE_INFO          = 4;

    /**
     * @var Messenger singleton instance of the class
     */
    private static $_instance;

    /**
     * @var SessionManager
     */
    private $_session;

    /**
     * @var array message repository
     */
    private $_messages = [];

    /**
     * Messenger constructor.
     * @param $session
     */
    private function __construct($session) {
        $this->_session = $session;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * @param SessionManager $session
     * @return Messenger a singleton instance
     */
    public static function getInstance(SessionManager $session)
    {
        if(self::$_instance === null) {
            self::$_instance = new self($session);
        }
        return self::$_instance;
    }

    /**
     * @param string $message the message to be added
     * @param int $type
     * Adds a new message to the repository
     */
    public function add($message, $type = self::APP_MESSAGE_SUCCESS)
    {
        if(!$this->messagesExists()) {
            $this->_session->messages = [];
        }
        $msgs = $this->_session->messages;
        $msgs[] = [$message, $type];
        $this->_session->messages = $msgs;
    }

    /**
     * @return bool true if message exists, false otherwise
     */
    private function messagesExists()
    {
        return isset($this->_session->messages);
    }

    /**
     * @return array|mixed the messages repository
     */
    public function getMessages()
    {
        if($this->messagesExists()) {
            $this->_messages = $this->_session->messages;
            unset($this->_session->messages);
            return $this->_messages;
        }
        return [];
    }
}