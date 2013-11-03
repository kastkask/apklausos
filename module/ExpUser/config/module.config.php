<?php
return array(
    'doctrine' => array(
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/ExpUser/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    'ExpUser\Entity' => 'zfcuser_entity',
                ),
            ),
        ),
    ),

    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'ExpUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),
    'controllers' => array(
        'invokables' => array(
            'ExpUser\Controller\User' => 'ExpUser\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'type' => 'Literal',
                'priority' => 1001,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
//                        '__NAMESPACE__' => 'ExpUser\Controller',
                        'controller' => 'ExpUser\Controller\User',
                        'action'     => 'index',
                    ),
                ),
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'ExpUser\Controller\User',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'ExpUser\Controller\User',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'changepassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-password',
                            'defaults' => array(
                                'controller' => 'ExpUser\Controller\User',
                                'action'     => 'changepassword',
                            ),
                        ),
                    ),
                ),
            ),

        ),
    ),

    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => 'ExpUser\Entity\Role',
            ),
        ),
        'guards' => array(

            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'zfcuser/logout', 'roles' => array('guest')),
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'zfcuser/register', 'roles' => array('guest')),
                array('route' => 'zfcuser', 'roles' => array('user')),
                array('route' => 'zfcuser/changepassword', 'roles' => array('user')),
                // Below is the default index action used by the ZendSkeletonApplication
                array('route' => 'home', 'roles' => array('guest', 'user')),
                array('route' => 'vote', 'roles' => array('guest', 'user')),
                array('route' => 'show', 'roles' => array('guest', 'user')),
                array('route' => 'questions', 'roles' => array('user')),
                array('route' => 'questions/add', 'roles' => array('user')),
                array('route' => 'questions/edit', 'roles' => array('user')),
                array('route' => 'questions/view', 'roles' => array('user')),
                array('route' => 'questions/delete', 'roles' => array('user')),
                array('route' => 'questions/email-list', 'roles' => array('user')),
                array('route' => 'questions/import-emails', 'roles' => array('user')),
                array('route' => 'questions/delete-email', 'roles' => array('user')),
                array('route' => 'questions/send', 'roles' => array('user')),
                array('route' => 'doctrine_orm_module_yuml', 'roles' => array('user')),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'ExpUser' => __DIR__ . '/../view',
        ),
    ),
);