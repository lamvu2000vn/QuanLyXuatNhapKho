<?php
declare(strict_types=1);

namespace User;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
           Controller\AuthController::class=>Controller\Factory\AuthControllerFactory::class,
           Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
           Controller\ProfileController::class=>Controller\Factory\ProfileControllerFactory::class,
           Controller\AdminController::class=>InvokableFactory::class,
           Controller\ItemController::class=>Controller\Factory\ItemControllerFactory::class,
           Controller\CategoryController::class=>Controller\Factory\CategoryControllerFactory::class,
           Controller\AjaxController::class=>InvokableFactory::class,
           Controller\AccountController::class=>Controller\Factory\AccountControllerFactory::class
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'tudien' => __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'slugin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/slugin',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'create',
                    ],
                ],
            ],
            
        
            'home' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/home',
                    
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
        
            'home' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/home',
                    
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'ajax' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/ajax',
                    
                    'defaults' => [
                        'controller' => Controller\AjaxController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'profile' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/profile',
                    'constraints'=>[
                        'id'=>'[0-9]+',
                        'username'=>'[a-zA-z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProfileController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,

                'child_routes' => [
                    'warehouse' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/warehouse[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\ProfileController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'item' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/item[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\ItemController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'category' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/category[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'account' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/account[/:action[/:id]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\AccountController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
            ],
        ],
        
    ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout_login.phtml',
            'layout/project'          => __DIR__ . '/../view/layout/admin.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
        'strategies' => array('ViewJsonStrategy',), 
    ],
    
];
?>