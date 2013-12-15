<?php

namespace QuestionsTest\Controller;


use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Questions\Controller\IndexController;
use Questions\Entity\Email;
use Questions\Entity\Question;
use QuestionsTest\Bootstrap;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;

class IndexControllerTest extends \PHPUnit_Framework_TestCase {
    protected $controller;
    protected $serviceManager;
    protected $request;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->controller = new IndexController();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Index'));
        $this->event = new MvcEvent();
        $config = $this->serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $response = new Response();
        $this->event->setResponse($response);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);
    }
    public function testIndexActionCanBeAccessed()
    {
        $mock = $this->getQuestionsServiceMethodMock('getQuestions', new \Doctrine\Common\Collections\ArrayCollection());

        $this->controller->setQuestionsService($mock);
        $result = $this->controller->indexAction();
        $variables = $result->getVariables();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertArrayHasKey('paginator', $variables);
        $this->assertInstanceOf('Zend\Paginator\Paginator', $variables['paginator']);
    }

    public function testCanViewQuestion()
    {
        $question = new Question();
        $question->setId(1);
        $this->controller->setQuestionsService($this->getQuestionsServiceMethodMock('getQuestion', $question));

        $this->routeMatch = new RouteMatch(array('controller' => 'Index', 'id'=>1));

        $this->event->setRouteMatch($this->routeMatch);
        $result = $this->controller->questionAction();

        $response = $this->controller->getResponse();

        $this->assertTrue(is_array($result));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('question', $result);
    }


    public function testGetActionNotFound()
    {
        $this->controller->setQuestionsService($this->getQuestionsServiceMethodMock('getQuestion', null));

        $this->routeMatch = new RouteMatch(array('controller' => 'Index', 'id'=>1));

        $this->event->setRouteMatch($this->routeMatch);
        $result = $this->controller->questionAction();

        $this->assertTrue($result->isRedirect());
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testAddActionCanShowForm()
    {
        $question = new Question;
        $question->setId(1);
        $this->controller->setQuestionForm($this->serviceManager->get('FormElementManager')->get('Questions\Form\QuestionForm'));
        $result = $this->controller->addAction();

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('form', $result);
        $this->assertEquals(200, $this->controller->getResponse()->getStatusCode());
    }

    public function testDeleteActionCanDelete()
    {
        $emMock = $this->getMock('Questions\Service\QuestionsService', array('getQuestion', 'removeQuestion'));

        $emMock->expects($this->once())
            ->method('getQuestion')
            ->will($this->returnValue(new Question));
        $emMock->expects($this->once())
            ->method('removeQuestion')
            ->will($this->returnValue(null));

        $this->controller->setQuestionsService($emMock);
        $result = $this->controller->deleteAction();

        $this->assertTrue($result->isRedirect());
        $this->assertEquals(302, $result->getStatusCode());
    }


    public function testCanSendQuestionToEmails()
    {
        $emMock = $this->getMock('Questions\Service\QuestionsService', array('getQuestion', 'sendQuestion'));

        $emMock->expects($this->once())
            ->method('getQuestion')
            ->will($this->returnValue(new Question));
        $emMock->expects($this->once())
            ->method('sendQuestion')
            ->will($this->returnValue(null));

        $this->controller->setQuestionsService($emMock);
        $result = $this->controller->sendAction();

        $this->assertTrue($result->isRedirect());
        $this->assertEquals(302, $result->getStatusCode());
    }

    public function testCanVote()
    {
        $this->controller->setQuestionsService($this->getQuestionsServiceMethodMock('vote', new Email()));
        $result = $this->controller->voteAction();

        $variables = $result->getVariables();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $this->assertArrayHasKey('email', $variables);
        $this->assertInstanceOf('Questions\Entity\Email', $variables['email']);
    }

    /***************************************
     * below is helpers for mocked objects
     **************************************/

    private function getQuestionsServiceMethodMock($method, $returnValue)
    {
        $emMock = $this->getMock('Questions\Service\QuestionsService', array($method));

        $emMock->expects($this->once())
            ->method($method)
            ->will($this->returnValue($returnValue));

        $hydrator = new ObjectHydrator($this->serviceManager->get('Doctrine\ORM\EntityManager'), 'Questions\Entity\Question');

        $emMock->expects($this->any())
            ->method('getHydrator')
            ->will($this->returnValue($hydrator));

        return $emMock;
    }
}
 