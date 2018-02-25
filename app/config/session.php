<?php

/*
 * Session configuration
 */

// Session name
defined('SESSION_NAME')     ? null : define ('SESSION_NAME', '_TRIVAGO_SESSION');

// Setting the session lifetime in seconds
defined('SESSION_LIFE_TIME')     ? null : define ('SESSION_LIFE_TIME', 0);

// setting the session storage folder
defined('SESSION_SAVE_PATH')     ? null : define ('SESSION_SAVE_PATH', APP_PATH . DS . '..' . DS . 'sessions');