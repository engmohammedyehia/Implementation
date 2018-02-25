<?php

/*
 * Defines the application default language
 * Available choices 'ar', 'en'
 * You can add more choices by defining other languages in the languages folder
 */

defined('APP_DEFAULT_LANGUAGE')     ? null : define ('APP_DEFAULT_LANGUAGE', 'en');

/*
 * Application Salt is used to encrypt passwords and as a salt key in any encryption process
 */
defined('APP_SALT')     ? null : define ('APP_SALT', '$2a$07$yeNCSNwRpYopOhv0TrrReP$');


/*
 * If set to 1 the application will be checking logged in users privileges to check
 * if they have access to current controller/action
 */
defined('CHECK_FOR_PRIVILEGES') ? null : define('CHECK_FOR_PRIVILEGES', 0);

/*
 * If set to 1 the application will check for authorized logins.
 */
defined('CHECK_FOR_AUTHORIZATION') ? null : define('CHECK_FOR_AUTHORIZATION', 0);