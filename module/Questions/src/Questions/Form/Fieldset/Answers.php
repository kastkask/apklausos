<?php
namespace Questions\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Doctrine\ORM\EntityManager;

class Answers extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @var EntityManager
     */
    public $_entityManager;

    public function init()
    {
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Text',
            'name' => 'answer',
            'options' => array(
                'label' => 'Answer',
            ),
            'attributes' => array(
                'required' => 'required',
            ),
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),
            'answer' => array(
                'required' => true,
            ),
        );
    }

    /**
     * @param EntityManager $entityManager
     * @return self
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->_entityManager = $entityManager;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->_entityManager;
    }
}