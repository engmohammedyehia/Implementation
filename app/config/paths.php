<?php

/*
 * General application paths
 */

// Application main path
define('APP_PATH', realpath(dirname(__FILE__)) . DS . '..');

// Views folder path
define('VIEWS_PATH', APP_PATH . DS . 'views' . DS);

// Template folder path
define('TEMPLATE_PATH', APP_PATH . DS . 'template' . DS);

// Language folders path
define('LANGUAGES_PATH', APP_PATH . DS . 'languages' . DS);

// Upload storage folder path
defined('UPLOAD_STORAGE')     ? null : define ('UPLOAD_STORAGE', APP_PATH . DS . '..' . DS . 'public' . DS . 'uploads');

// Images storage
defined('IMAGES_UPLOAD_STORAGE')     ? null : define ('IMAGES_UPLOAD_STORAGE', UPLOAD_STORAGE . DS . 'images');

// Documents storage
defined('DOCUMENTS_UPLOAD_STORAGE')     ? null : define ('DOCUMENTS_UPLOAD_STORAGE', UPLOAD_STORAGE . DS . 'documents');

// Setting the maximum upload size based on the ini predefined value
defined('MAX_FILE_SIZE_ALLOWED')     ? null : define ('MAX_FILE_SIZE_ALLOWED', ini_get('upload_max_filesize'));

// CSS default web path
define('CSS', '/css/');

// JavaScript default web path
define('JS', '/js/');

// Documents storage default web path
define('DOCUMENT_UPLOAD_WEB_FOLDER', '/uploads/documents/');

// Images storage default web path
define('IMAGES_UPLOAD_WEB_FOLDER', '/uploads/images/');