<?php
namespace PHPMVC\LIB\Template;
use PHPMVC\Lib\Registry;

/**
 * Class Template
 * @package PHPMVC\LIB\Template
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class Template
{
    use TemplateHelper;

    /**
     * @var mixed an array of template configuration
     * @example
     return [
        'template' => [
            ':view'             => ':action_view'
        ],
        'header_resources' => [
            'css' => [
                'normalize'         => CSS . 'normalize.css',
                'fawsome'           => CSS . 'fawsome.min.css',
                'gicons'            => CSS . 'googleicons.css',
                'main'              => CSS . 'main.' . $_SESSION['lang'] . '.css',
                'form'              => CSS . 'form.' . $_SESSION['lang'] . '.css'
            ],
            'js' => [
                'modernizr'         => JS . 'vendor/modernizr-2.8.3.min.js'
            ]
        ],
        'footer_resources' => [
                'jquery'                => JS . 'vendor/jquery-1.12.0.min.js',
                'helper'                => JS . 'helper.js',
                'main'                  => JS . 'main.js'
        ]
    ];
     */
    private $_templateParts;

    /**
     * @var the action view name to be loaded
     */
    private $_action_view;

    /**
     * @var the data to be extracted to the view
     */
    private $_data;

    /**
     * @var Registry the registry object
     */
    private $_registry;

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->_registry->$key;
    }

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->_templateParts = require_once TEMPLATE_PATH . 'config.php';
    }

    /**
     * @param $actionViewPath
     * Sets the action view to be loaded
     */
    public function setActionViewFile($actionViewPath)
    {
        $this->_action_view = $actionViewPath;
    }

    /**
     * @param $data
     * Sets the application data to be extracted with to the loaded view
     */
    public function setAppData($data)
    {
        $this->_data = $data;
    }

    /**
     * @param $template sets the template parts
     */
    public function swapTemplate($template)
    {
        $this->_templateParts['template'] = $template;
    }

    /**
     * @param $registry
     * sets the registry objects
     */
    public function setRegistry($registry)
    {
        $this->_registry = $registry;
    }

    /**
     * renders the header start part
     */
    private function renderTemplateHeaderStart()
    {
        extract($this->_data);
        require_once TEMPLATE_PATH . 'templateheaderstart.php';
    }

    /**
     * renders the header end part
     */
    private function renderTemplateHeaderEnd()
    {
        extract($this->_data);
        require_once TEMPLATE_PATH . 'templateheaderend.php';
    }

    /**
     * renders the footer part
     */
    private function renderTemplateFooter()
    {
        extract($this->_data);
        require_once TEMPLATE_PATH . 'templatefooter.php';
    }

    /**
     * renders the template parts with the view
     */
    private function renderTemplateBlocks()
    {
        if(!array_key_exists('template', $this->_templateParts)) {
            trigger_error('Sorry you have to define the template blocks', E_USER_WARNING);
        } else {
            $parts = $this->_templateParts['template'];
            if(!empty($parts)) {
                extract($this->_data);
                foreach ($parts as $partKey => $_file_) {
                    if($partKey === ':view') {
                        require_once $this->_action_view;
                    } else {
                        require_once $_file_;
                    }
                }
            }
        }
    }

    /**
     * @return string
     * renders the header resources (CSS, JS)
     */
    private function renderHeaderResources()
    {
        $output = '';
        if(!array_key_exists('header_resources', $this->_templateParts)) {
            trigger_error('Sorry you have to define the header resources', E_USER_WARNING);
        } else {
            $resources = $this->_templateParts['header_resources'];

            // Generate CSS Links
            $css = $resources['css'];
            if(!empty($css)) {
                foreach ($css as $cssKey => $path) {
                    $output .= '<link type="text/css" rel="stylesheet" href="' . $path . '" />';
                }
            }
            // Generate JS Scripts
            $js = $resources['js'];
            if(!empty($js)) {
                foreach ($js as $jsKey => $path) {
                    $output .= '<script src="' . $path . '"></script>';
                }
            }
        }
        return $output;
    }

    /**
     * @return string
     * renders the footer resource (JS)
     */
    private function renderFooterResources()
    {
        $output = '';
        if(!array_key_exists('footer_resources', $this->_templateParts)) {
            trigger_error('Sorry you have to define the footer resources', E_USER_WARNING);
        } else {
            $resources = $this->_templateParts['footer_resources'];

            // Generate JS Scripts
            if(!empty($resources)) {
                foreach ($resources as $resourceKey => $path) {
                    $output .= '<script src="' . $path . '"></script>';
                }
            }
        }
        return $output;
    }

    /**
     * renders the full built template
     */
    public function renderApp()
    {
        ob_start();
        $this->renderTemplateHeaderStart();
        echo $this->renderHeaderResources();
        $this->renderTemplateHeaderEnd();
        $this->renderTemplateBlocks();
        echo $this->renderFooterResources();
        $this->renderTemplateFooter();
        ob_get_contents();
        ob_flush();
    }
}