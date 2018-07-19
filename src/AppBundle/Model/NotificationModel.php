<?php
namespace AppBundle\Model;

use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationModel{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;


    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $notificationJson string
     * @return Notification
     */
    public function processNotification($notificationJson)
    {
        $notificationDecoded = json_decode($notificationJson);

        $dateNotification = new \DateTime($notificationDecoded->date);

        $notification = new Notification();
        $notification->setTransactionId($notificationDecoded->transaction_id);
        $notification->setStatus($notificationDecoded->status);
        $notification->setDate($dateNotification);
        $notification = $this->saveNotification($notification);

        return $notification;

    }

    /**
     * @param Notification $notification
     * @return Notification
     */
    public function saveNotification(Notification $notification){
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification;

    }


}