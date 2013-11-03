<?php
namespace Questions\Factory;

use Questions\Entity\Answer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Stdlib\Hydrator\Strategy;

class AnswersFieldsetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $hydrator = new DoctrineHydrator($entityManager, 'Questions\Entity\Answer');

        $form = new \Questions\Form\Fieldset\Answers;
        $form
            ->setEntityManager($entityManager)
            ->setHydrator($hydrator)
            ->setObject(new Answer())
        ;

        return $form;
    }
}