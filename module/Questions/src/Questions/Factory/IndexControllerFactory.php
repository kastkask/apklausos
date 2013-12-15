<?php
namespace Questions\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();
        $controller = new \Questions\Controller\IndexController();
        $controller
            ->setQuestionsService($sm->get('Questions\Service\QuestionService'))
            ->setQuestionForm($sm->get('FormElementManager')->get('Questions\Form\QuestionForm'));

        return $controller;
    }
}