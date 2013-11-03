<?php
namespace Questions\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Doctrine\ORM\EntityManager;

class Question extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @var EntityManager
     */
    public $_entityManager;

    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'question',
            'required' => true,
            'attributes' => array(
                'type'  => 'text',
                'id'  => 'question',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Question',
            ),
        ));

        // TODO: [SECURITY] needs validator to check if answer belongs to this question or if creating question answer must must have id of null
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'answers',
            'options' => array(
                'label' => 'Question answers',
                'count' => 2,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => false,
                'target_element' => array(
                    'type' => 'Questions\Form\Fieldset\Answers',
                ),
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
            'question' => array(
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