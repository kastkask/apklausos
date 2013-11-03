<?php
namespace Questions\Factory;

use Questions\Entity\Question;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Stdlib\Hydrator\Strategy;

class QuestionFieldsetFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $hydrator = new DoctrineHydrator($entityManager, 'Questions\Entity\Question');
        $hydrator->addStrategy('answers', new Strategy\DisallowRemoveByValue());
        $hydrator->addStrategy('answers', new Strategy\DisallowRemoveByReference());

        $form = new \Questions\Form\Fieldset\Question('question');
        $form
            ->setEntityManager($entityManager)
            ->setHydrator($hydrator)
            ->setObject(new Question());

        return $form;
    }
}