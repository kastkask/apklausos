<?php
namespace ExpUser;

use Zend\Mvc\MvcEvent;

class Module {
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $zfcServiceEvents = $mvcEvent->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();
        $zfcServiceEvents->attach('register', function($e) use($mvcEvent) {
            $user = $e->getParam('user');
            $em = $mvcEvent->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
            $defaultUserRole = $em->getRepository('ExpUser\Entity\Role')->findOneBy(array('roleId' => 'user'));
            $user->addRole($defaultUserRole);
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}