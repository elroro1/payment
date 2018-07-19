<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * @Route("/constructRabbit", name="testrabbit")
     */
    public function constructRabbitNotificationQueueAction()
    {
        $msg = '{"transaction_id":"TRX_1","status":100,"date":"2013/08/01 00:00"}';
        $this->get('old_sound_rabbit_mq.notification_producer')->publish($msg);


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
