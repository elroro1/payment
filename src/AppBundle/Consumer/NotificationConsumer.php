<?php

namespace AppBundle\Consumer;

use AppBundle\Entity\Transaction;
use AppBundle\Model\NotificationModel;
use AppBundle\Model\TransactionModel;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class NotificationConsumer implements ConsumerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var NotificationModel
     */
    private $notificationModel ;

    /**
     * @var TransactionModel
     */
    private $transactionModel ;

    /**
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * NotificationConsumer constructor.
     * @param LoggerInterface $logger
     * @param TransactionModel $transactionModel
     * @param NotificationModel $notificationModel
     */
    public function __construct(LoggerInterface $logger, TransactionModel $transactionModel, NotificationModel $notificationModel)
    {
        $this->logger = $logger;
        $this->notificationModel = $notificationModel;
        $this->transactionModel = $transactionModel;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        try{

            $this->logger->info(sprintf('proccess message %s', $msg->body));
            if (!$data = json_decode($msg->body, true)) {
                $this->logger->error('Invalid message body');

                return ConsumerInterface::MSG_REJECT;
            }

            $notification = $this->notificationModel->processNotification($msg->body);
            $this->logger->info(
                sprintf(
                    'Notification %s for transaction_id %s with status %s and date %s persisted, transaction in progress...',
                    $notification->getId(),
                    $notification->getTransactionId(),
                    $notification->getStatus(),
                    $notification->getDate()->format('Y-m-d')
                )
            );
            $transaction = $this->transactionModel->processTransaction($notification);

            $this->logger->info(
                sprintf(
                    'Transaction  %s processed ' ,
                    $transaction->getTransactionId()
                )
            );

            if ($transaction instanceof Transaction) {
                return ConsumerInterface::MSG_ACK;
            } else {
                throw new \Exception('Unknow error, transaction not persisted');
            }

            gc_collect_cycles();
        } catch (\Exception $exception){
            $this->logger->error(sprintf(
                '[NotificationConsumer] - execute() => error during process code : %s, message : %s' ,
                $exception->getCode(),
                $exception->getMessage()
            ));
            return ConsumerInterface::MSG_REJECT;

        }
    }
}
