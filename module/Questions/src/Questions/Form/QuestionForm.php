<?php
namespace Questions\Form;

use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class QuestionForm extends Form
{
    /**
     * @var EntityManager
     */
    public $_entityManager;

    public function init()
    {
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-default'
            ),
        ));

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