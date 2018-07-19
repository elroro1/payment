<?php
namespace AppBundle\Model;


use AppBundle\Entity\Notification;
use AppBundle\Entity\Transaction;

class TransactionModel {


    const CODE_AVAILABLE = 100;
    const CODE_CAPTURED = 200;
    const CODE_BILLED = 300;
    const CODE_CANCELLED = 600;
    const CODE_REFUNDED = 700;
    const CODE_CHARGEBACKED = 800;
    const CODE_ERROR = 900;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;


    /**
     * @param EntityManager $entityManager
     */
    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param Notification $notification
     * @return Transaction|null|object
     */
    public function processTransaction(Notification $notification)
    {
        $transaction = $this->entityManager->find('AppBundle:Transaction',$notification->getTransactionId());
        $date = new \DateTime('now');
        // Cas nouvelle transaction
        if (!$transaction instanceof Transaction || empty($transaction)){

            // on part du principe qu'un création de transaction ne peut etre qu'au statut 100 ( parti pris)
            if (!in_array($notification->getStatus(), array(SELF::CODE_AVAILABLE))){
                throw new \Exception(sprintf('Cannot create transaction with status not equal to %s', self::CODE_AVAILABLE));
            }
            $transaction = new Transaction();

            $transaction->setDateCreate($notification->getDate());
            $transaction->setTransactionId($notification->getTransactionId());
            $transaction->setDateUpdate($date);


        }
        // Cas transaction existante
        switch ($transaction->getStatus()){
            case self::CODE_AVAILABLE:
                if (
                    !in_array($notification->getStatus(),
                    array(
                        SELF::CODE_CAPTURED,
                        SELF::CODE_BILLED,
                        SELF::CODE_CANCELLED,
                        SELF::CODE_ERROR
                    )
                )){
                    $this->throwException($notification, $transaction);

                }
                break;
            case self::CODE_CAPTURED:
                if (
                !in_array($notification->getStatus(),
                    array(
                        SELF::CODE_BILLED,
                        SELF::CODE_REFUNDED,
                        SELF::CODE_CHARGEBACKED,
                        SELF::CODE_ERROR
                    )
                )){
                    $this->throwException($notification, $transaction);
                }
                break;
            case self::CODE_BILLED:
                if (
                    !in_array($notification->getStatus(),
                    array(
                        SELF::CODE_REFUNDED,
                        SELF::CODE_CHARGEBACKED,
                        SELF::CODE_ERROR
                    )
                )){
                    $this->throwException($notification, $transaction);
                }
                break;
            case self::CODE_CANCELLED:
                $this->throwException($notification, $transaction);
                break;
            case self::CODE_REFUNDED:
                $this->throwException($notification, $transaction);
                break;
            case self::CODE_CHARGEBACKED:
                $this->throwException($notification, $transaction);
                break;
            case self::CODE_ERROR:
                $this->throwException($notification, $transaction);
                break;

        }
        switch ($notification->getStatus()) {
            CASE self::CODE_AVAILABLE:
                $transaction->setDateAuthorized($notification->getDate());
                $transaction->setDateUpdate($date);
                break;
            CASE self::CODE_CAPTURED:
                $transaction->setDateCaptured($notification->getDate());
                $transaction->setDateUpdate($date);

                break;
            CASE self::CODE_BILLED:
                $transaction->setDateSettled($notification->getDate());
                $transaction->setDateUpdate($date);

                break;
            CASE self::CODE_CANCELLED:
                $transaction->setDateUnpaid($notification->getDate());
                $transaction->setDateUpdate($date);

                break;
            CASE self::CODE_REFUNDED:
                $transaction->setDateUpdate($date);

                break;
            CASE self::CODE_CHARGEBACKED:
                $transaction->setDateUpdate($date);

                break;
            CASE self::CODE_ERROR:
                $transaction->setDateUpdate($date);

                break;
        }

        $transaction->setStatus($notification->getStatus());
        $this->saveTransaction($transaction);

        return $transaction;
    }

    private function throwException($notification, $transaction){
        throw new \Exception(
            sprintf(
                'Wrong Notification status %s for existing transaction %s with status %s',
                $notification->getStatus(),
                $transaction->getTransactionId(),
                $transaction->getStatus()
            )
        );
    }


    /**
     * @param Transaction $transaction
     * @return Transaction
     */
    public function saveTransaction(Transaction $transaction){

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $transaction;
    }


}