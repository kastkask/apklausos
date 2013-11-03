<?php
namespace Questions\Factory;

use Questions\Form\Fieldset\Question as QuestionFieldset;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use DoctrineModule\Stdlib\Hydrator\Strategy;

class ImportEmailFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new \Questions\Form\ImportEmailForm('email_import_form');
        $form->setEntityManager($entityManager);

        return $form;
    }
}