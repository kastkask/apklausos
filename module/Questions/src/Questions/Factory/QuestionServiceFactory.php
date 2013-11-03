<?php
namespace Questions\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuestionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $userService = $serviceLocator->get('zfcuser_auth_service');
        $viewRenderer = $serviceLocator->get('ViewRenderer');

        $question_service = new \Questions\Service\QuestionService();
        $question_service
            ->setEntityManager($entityManager)
            ->setAuthenticationService($userService)
            ->setViewRenderer($viewRenderer);

        return $question_service;
    }
}