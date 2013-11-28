<?php

namespace Questions\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineModule\Paginator\Adapter\Collection as CollectionAdapter;
use Zend\Paginator\Paginator;

class EmailController extends AbstractActionController
{
    public function indexAction()
    {
        $id = (int)$this->params('question');
        $question_service = $this->getServiceLocator()->get('Questions\Service\QuestionService');
        $question = $question_service->getQuestion($id);

        if (!$question) {
            return $this->redirect()->toRoute('questions');
        }

        // Create the adapter
        $adapter = new CollectionAdapter($question->getEmails());

        // Create the paginator itself
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'))
            ->setItemCountPerPage(50);

        return new ViewModel(array(
            'question' => $question,
            'paginator' => $paginator
        ));
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        $question_service = $this->getServiceLocator()->get('Questions\Service\QuestionService');
        $email = $question_service->getEmail($id);

        if (!$email) {
            return $this->redirect()->toRoute('questions');
        }

        $question = $email->getQuestion();

        $question_service->removeEmail($email);

        // Redirect to list of questions
        return $this->redirect()->toRoute('questions/email-list', array('question' => $question->getId()));
    }

    // TODO: validation needs optimization
    public function importAction()
    {
        $id = (int)$this->params('question');

        $question_service = $this->getServiceLocator()->get('Questions\Service\QuestionService');
        $question = $question_service->getQuestion($id);

        if (!$question) {
            return $this->redirect()->toRoute('questions');
        }

        $form = $this->getServiceLocator()->get('FormElementManager')->get('Questions\Form\ImportEmailForm');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            //set data post and file ...
            $form->setData($data);

            if ($form->isValid()) {
                $file = $this->params()->fromFiles('fileupload');
                $question_service->importEmails($question, $file);

                // Redirect to list of emails
                return $this->redirect()->toRoute('questions/email-list', array('question' => $question->getId()));
            }
        }

        return array(
            'question' => $question,
            'form' => $form,
        );
    }
}
