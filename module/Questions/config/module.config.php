<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'question_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/Questions/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Questions\Entity' => 'question_entity',
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'vote' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/vote/:email/:answer',
                    'constraints' => array(
                        'email'     => '[0-9]+',
                        'answer'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Questions\Controller',
                        'controller' => 'Index',
                        'action'     => 'vote',
                    ),
                ),
            ),
            'show' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/images/:email/fake.png',
                    'constraints' => array(
                        'email'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Questions\Controller',
                        'controller' => 'Index',
                        'action'     => 'show',
                    ),
                ),
            ),
            'questions' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/questions[/page/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Questions\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'page'          => 1,
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'view' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action'     => 'question',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/new',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action'     => 'add',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action'     => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action'     => 'delete',
                            ),
                        ),
                    ),

                    'email-list' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/emails/:question[/page/:page]',
                            'constraints' => array(
                                'question'     => '[0-9]+',
                                'page'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Email',
                                'action'     => 'index',
                                'page' => 1,
                            ),
                        ),
                    ),
                    'import-emails' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:question/emails/import',
                            'constraints' => array(
                                'question'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Email',
                                'action'     => 'import',
                            ),
                        ),
                    ),
                    'delete-email' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/email/delete/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Email',
                                'action'     => 'delete',
                            ),
                        ),
                    ),
                    'send' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/send/:question',
                            'constraints' => array(
                                'question'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action'     => 'send',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Questions\Controller\Index' => 'Questions\Controller\IndexController',
            'Questions\Controller\Email' => 'Questions\Controller\EmailController',
        ),
    ),

    'form_elements' => array(
        'factories' => array(
            'Questions\Form\Fieldset\Answers' => 'Questions\Factory\AnswersFieldsetFactory',
            'Questions\Form\Fieldset\Question' => 'Questions\Factory\QuestionFieldsetFactory',
            'Questions\Form\QuestionForm' => 'Questions\Factory\QuestionFormFactory',
            'Questions\Form\ImportEmailForm' => 'Questions\Factory\ImportEmailFormFactory',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Questions\Service\QuestionService' => 'Questions\Factory\QuestionServiceFactory',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'Questions' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'emails_pagination'         => __DIR__ . '/../view/questions/email/paginator.phtml',
            'pagination'                => __DIR__ . '/../view/questions/index/paginator.phtml',
            'email/template/question'   => __DIR__ . '/../view/questions/templates/question.phtml',
        ),
    ),
);