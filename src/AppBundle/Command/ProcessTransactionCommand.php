<?php

namespace AppBundle\Command;

use AppBundle\Model\NotificationModel;
use AppBundle\Model\TransactionModel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessTransactionCommand extends ContainerAwareCommand
{

    /**
     * @var NotificationModel
     */
    private $notificationModel ;

    /**
     * @var TransactionModel
     */
    private $transactionModel ;

    /**
     * ProcessTransactionCommand constructor.
     * @param TransactionModel $transactionModel
     * @param NotificationModel $notificationModel
     */
    public function __construct(TransactionModel $transactionModel, NotificationModel $notificationModel)
    {
        parent::__construct();

        $this->notificationModel = $notificationModel;
        $this->transactionModel = $transactionModel;

    }


    protected function configure()
    {
        $this
            ->setName('deezer:process:transaction')
            ->setDescription(
                'Handle the whole process of creating/updating transaction, 
                get a notification from entry in Json format, return an error in case of wrong notification status'
            )
            ->addArgument('notification', InputArgument::REQUIRED, ' json encoded notification ')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('Begin command execution.');

        $notification = $this->notificationModel->processNotification($input->getArgument('notification'));

        $output->writeln(
            sprintf(
                'Notification %s for transaction_id %s with status %s and date %s persisted, transaction in progress...',
                $notification->getId(),
                $notification->getTransactionId(),
                $notification->getStatus(),
                $notification->getDate()->format('Y-m-d')
            )
        );

        $transaction = $this->transactionModel->processTransaction($notification);

        $output->writeln(
            sprintf(
                'Transaction  %s processed ' ,
                $transaction->getTransactionId()
            )
        );

        $output->writeln('End');

    }

}
