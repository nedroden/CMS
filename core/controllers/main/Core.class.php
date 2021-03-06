<?php
/**
 * WIP CMS
 * Open source content management system
 *
 * @version 1.0 Alpha 1
 * @author Aeros Development
 * @copyright 2018, WIP CMS
 * @link https://aeros.com/wipcms
 *
 * @license MIT
 */

namespace WIPCMS\core\controllers\main;

use WIPCMS\core\common\{Middleware, Registry};

class Core {

    private $_router;

    public function __construct() {
        $this->_router = Registry::retrieve('router');
    }

    public function run() : void {
        $this->loadModule();
    }

    private function loadModule() : void {
        $route = $this->_router->getCurrentRoute();

        if (count($route) === 0)
            die(sprintf('Unable to get route: %s on line %u', __FILE__, __LINE__));

        /**
         * @todo When the plugin system is being worked on, replace the following line
         */

        $call = explode('@', $route[0]);
        if (count($call) !== 2)
            die(sprintf('Route not declared correctly, should be Class@Function: %s on line %u', __FILE__, __LINE__));

        $class = 'WIPCMS\core\controllers\\' . $call[0];
        $controller = new $class();

        if (count($route) > 2)
            Middleware::checkRequest($route);

        $controller->{$call[1]}($route);
    }
}