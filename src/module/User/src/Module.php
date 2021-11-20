<?php
declare(strict_types=1);

namespace User;

use Laminas\Db\Adapter\Adapter;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;
use User\Model\Table\UserTable;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole as Role;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        $this->getServiceConfig();
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getServiceConfig():array{
        return[
            'factories'=>[
                UserTable::class=>function($sm){
                    $dbAdapter = $sm->get(Adapter::class);
                    return new UserTable($dbAdapter);
                }
            ]
            ];
    }
    public function onBootstrap(MvcEvent $e) {
         $this -> initAcl($e);
         $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));
         //$this->checkAcl($e);
        }
     public function initAcl(MvcEvent $e) {
        $acl = new Acl();
        $roles = include __DIR__ . '/../config/module.acl.roles.php';
        $allResources = array();
        
        $test=0;
        foreach ($roles as $role => $resources) {
            $test ++;
            $acl->addRole(new Role($role));
            if($test==2)
            {
                $parents = array('guest', 'admin');
                $acl->addRole(new Role('specialuser'), $parents);
            }
           
            $allResources = array_merge($resources, $allResources);
           
            
            //adding resources
            foreach ($resources as $resource) {
                 // Edit 4
                 if(!$acl ->hasResource($resource))
                 {
                    $acl -> addResource(new Resource($resource));
                 }
            }
            //adding restrictions
            foreach ($allResources as $resource) {
                $acl -> allow($role, $resource);
            }
        }
        $acl->deny('specialuser', ['add-user']);
        //testing
        // $acl->allow('specialuser',null,array('add-user','login'));
        //var_dump($acl->isAllowed('specialuser',null,'home'));
        //var_dump($acl->isAllowed('guest','profile/item/add'));
        //true
        //echo $acl->isAllowed('guest', 'admin') ? 'allowed' : 'denied';
        $e -> getViewModel() -> acl = $acl;
     
    }
    public function checkAcl(MvcEvent $e) {
        $route = $e -> getRouteMatch() -> getMatchedRouteName();
        $routeParams = $e-> getRouteMatch () -> getParams ();
        //var_dump($routeParams);
        //var_dump($route);
        //you set your role
        $userRole = 'admin';
        //echo 0;
       // var_dump( $e -> getViewModel() -> acl -> isAllowed($userRole, $route));
        //var_dump($e->getViewModel()->acl->hasResource($route));
        //echo $userRole;
        //var_dump(!$e -> getViewModel() -> acl -> isAllowed($userRole, $route));
        if (!$e->getViewModel()->acl->hasResource($route)||!$e -> getViewModel() -> acl -> isAllowed($userRole, $route)) {
            $response = $e -> getResponse();
            //location to page or what ever
            $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
            $response -> setStatusCode(404);
     
        }
        
    }
    public function getDbRoles(MvcEvent $e){
        // I take it that your adapter is already configured
        $dbAdapter = $e->getApplication()->getServiceManager()->get('Laminas\Db\Adapter\Adapter');
        $results = $dbAdapter->query('SELECT * FROM acl');
        // making the roles array
        $roles = array();
        foreach($results as $result){
            $roles[$result['user_role']][] = $result['resource'];
        }
        return $roles;
    }
    
}

?>