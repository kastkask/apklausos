<?php
namespace Questions\Factory;

use Questions\Form\Fieldset\Question as QuestionFieldset;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use DoctrineModule\Stdlib\Hydrator\Strategy;

class QuestionFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');



        $hydrator = new DoctrineHydrator($entityManager, 'Questions\Entity\Question');
        $hydrator->addStrategy('answers', new Strategy\DisallowRemoveByValue());

        $form = new \Questions\Form\QuestionForm('question_form');
        $form
            ->setEntityManager($entityManager)
            ->setHydrator($hydrator);

        $questionFieldset = $serviceLocator->get('Questions\Form\Fieldset\Question');
        $questionFieldset->setUseAsBaseFieldset(true);
        $form->add($questionFieldset);

        return $form;
    }
}