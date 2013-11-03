<?php

namespace Questions\Service;

use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator;
use Zend\Authentication\AuthenticationService;
use Questions\Entity\Question;
use Questions\Entity\Email;
use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;


class QuestionService
{
    public $_viewRenderer;
    /**
     * @var UserService
     */
    protected $_authenticationService;

    /**
     * @var ServiceManager
     */
    protected $_serviceManager;

    /**
     * @var Hydrator\ClassMethods
     */
    protected $_formHydrator;


    public function getQuestion($id)
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        $objectManager = $this->getEntityManager()->getRepository('Questions\Entity\Question');

        $question = $objectManager->findOneBy(array(
            'user' => $identity->getId(),
            'id' => $id
        ));

        return $question;
    }

    public function getEmail($id, $valid = true)
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        $objectManager = $this->getEntityManager()->getRepository('Questions\Entity\Email');

        $email = $objectManager->findOneBy(array(
            'id' => $id
        ));

        if (!$email || ($valid && $email->getQuestion()->getUser()->getId() != $identity->getId()))
            return null;

        return $email;
    }

    public function removeEmail(Email $email)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($email);
        $entityManager->flush();

        return $this;
    }

    public function removeQuestion(Question $question)
    {
        $entityManager = $this->getEntityManager();

        $emails = $question->getEmails();
        foreach ($emails as $email)
            $entityManager->remove($email);

        $answers = $question->getAnswers();
        foreach($answers as $answer)
            $entityManager->remove($answer);

        $entityManager->remove($question);
        $entityManager->flush();

        return $this;
    }

    public function getQuestions()
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        return $identity->getQuestions();
    }

    public function sendQuestion(Question $question)
    {
        $emails = $question->getEmails();
        $transport = new Mail\Transport\Sendmail();
        foreach($emails as $email)
        {
            $view_renderer = $this->getViewRenderer();
            $content = $view_renderer->render('email/template/question',
                array(
                    'email' => $email,
                    'question' => $question
                ));

            // make a header as html
            $html = new MimePart($content);
            $html->type = "text/html";
            $body = new MimeMessage();
            $body->setParts(array($html,));

            $mail = new Mail\Message();
            $mail->setBody($body);
            $mail->setFrom('noreplay@misc.lt', 'Questions App');
            $mail->addTo($email->getEmail());
            $mail->setSubject('New Question');

            $transport->send($mail);
            $email->setState(Email::STATE_SENT);
        }
        $this->getEntityManager()->flush();
    }
    public function create(Question $question)
    {
        // setting question owner
        $identity = $this->getAuthenticationService()->getIdentity();
        $question->setUser($identity);

        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush();

        return $question;
    }

    public function update(Question $question)
    {
        $identity = $this->getAuthenticationService()->getIdentity();
        if ($question->getUser()->getId() != $identity->getId())
        {
            throw new \Exception('You do not have access to this question.');
        }

        $this->getEntityManager()->persist($question);
        $this->getEntityManager()->flush();

        return $question;
    }

    public function updateEmailStatus(Email $email, $status)
    {
        if ($email->getState() < $status)
        {
            $email->setState($status);
            $this->getEntityManager()->persist($email);
            $this->getEntityManager()->flush();
        }
    }


    public function vote($email, $answer)
    {
        $entityManager = $this->getEntityManager();

        $email = $this->getEmail($email);
        $answer = $entityManager->find('Questions\Entity\Answer', $answer);

        if ($email->getQuestion()->getId() !== $answer->getQuestion()->getId()) {
            return null;
        }

        $email->setAnswer($answer);
        $email->setState(Email::STATE_ANSWERED);
        $entityManager->flush();

        return $email;
    }

    public function importEmails(Question $question, $file)
    {
        $entityManager = $this->getEntityManager();
        $parser = new \Questions\Util\FileParser;
        $emails = $parser->parseFile($file);
        $emails = $parser->removeCurrentEmails($question->getEmails(), $emails);

        foreach($emails as $email)
        {
            $email_entity = new \Questions\Entity\Email;
            $email_entity->setEmail($email);
            $email_entity->setState(\Questions\Entity\Email::STATE_NEW);

//            $entityManager->persist($email_entity);
            $question->addEmail($email_entity);
        }

        $entityManager->flush();
    }

    public function setEntityManager(EntityManager $entity_manager)
    {
        $this->_entityManager = $entity_manager;
        return $this;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_entityManager;;
    }

    /**
     * Return the Form Hydrator
     *
     * @throws \Exception
     * @return Hydrator
     */
    public function getFormHydrator()
    {
        if (!$this->_formHydrator) {
            throw new \Exception('Hydrator was not set.');
        }

        return $this->_formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param Hydrator $formHydrator
     * @return self
     */
    public function setFormHydrator(Hydrator $formHydrator)
    {
        $this->_formHydrator = $formHydrator;
        return $this;
    }

    /**
     * @param AuthenticationService $authenticationService
     * @return self
     */
    public function setAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->_authenticationService = $authenticationService;
        return $this;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->_authenticationService;
    }

    public function setViewRenderer($view_renderer)
    {
        $this->_viewRenderer = $view_renderer;
        return $this;
    }

    public function getViewRenderer()
    {
        return $this->_viewRenderer;
    }
}
