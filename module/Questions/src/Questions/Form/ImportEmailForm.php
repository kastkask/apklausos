<?php
namespace Questions\Form;

use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ImportEmailForm extends Form implements InputFilterProviderInterface
{
    /**
     * @var EntityManager
     */
    public $_entityManager;

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'Upload CSV File',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Import',
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

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'fileupload' => array(
                'required' => true,

                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\File\Extension',
                        'options' => array(
                            'extension' =>  array('csv'),
                            'case' => true,
                        ),
                    ),
                    array(
                        'name' => 'Zend\Validator\File\Size',
                        'options' => array(
                            'max' => '2MB'
                        ),
                    ),
                )
            ),
        );
    }
}