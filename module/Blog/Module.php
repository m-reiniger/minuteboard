<?php

namespace Blog;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

use Doctrine\ODM\MongoDB\Types\Type;

//use Blog\Model\Message;
//use Blog\Model\MessageTable;
//use Zend\Db\ResultSet\ResultSet;
//use Zend\Db\TableGateway\TableGateway;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /*public function getServiceConfig(){
        return array(
            'factories' => array(
                'Blog\Model\MessageTable' =>  function($sm) {
                    $tableGateway = $sm->get('MessageTableGateway');
                    $table = new MessageTable($tableGateway);
                    return $table;
                },
                'MessageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Message());
                    return new TableGateway('message', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }*/


    public function onBootstrap(MvcEvent $e) {

        //register new Doctrine type
        Type::addType('mongodate', 'Blog\Types\MongoDateType');


        $sm = $e->getApplication()->getServiceManager();

        $router = $sm->get('router');
        $request = $sm->get('request');
        $matchedRoute = $router->match($request);

        if($matchedRoute) {
            $params = $matchedRoute->getParams();

            $controller = $params['controller'];
            $action = $params['action'];

            $module_array = explode('\\', $controller);
            $module = array_pop($module_array);

            $route = $matchedRoute->getMatchedRouteName();

            $e->getViewModel()->setVariables(
                array(
                    'module' => $module,
                    'controller' => strtolower(substr(strrchr($controller, '\\'), 1)),
                    'action' => $action,
                    'route' => $route,
                )
            );
        }
    }

}
