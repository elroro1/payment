<?php
namespace AppBundle\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * DeezerPayment.transaction
 *
 * @ORM\Table(name="deezer_payment.transaction", indexes={@ORM\Index(name="IDX_CDB59BA37B00651C", columns={"status"})})
 * @ORM\Entity
 */
class Transaction
{
    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=45, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\SequenceGenerator(sequenceName="deezer_payment.transaction_id_seq", allocationSize=1, initialValue=1)
     */
    private $transactionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetimetz", nullable=false)
     */
    private $dateCreate = 'now()';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetimetz", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_authorized", type="datetimetz", nullable=true)
     */
    private $dateAuthorized;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_captured", type="datetimetz", nullable=true)
     */
    private $dateCaptured;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_settled", type="datetimetz", nullable=true)
     */
    private $dateSettled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_unpaid", type="datetimetz", nullable=true)
     */
    private $dateUnpaid;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;



    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @param DateTime $dateCreate
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * @param DateTime $dateUpdate
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    }

    /**
     * @return DateTime
     */
    public function getDateAuthorized()
    {
        return $this->dateAuthorized;
    }

    /**
     * @param DateTime $dateAuthorized
     */
    public function setDateAuthorized($dateAuthorized)
    {
        $this->dateAuthorized = $dateAuthorized;
    }

    /**
     * @return DateTime
     */
    public function getDateCaptured()
    {
        return $this->dateCaptured;
    }

    /**
     * @param DateTime $dateCaptured
     */
    public function setDateCaptured($dateCaptured)
    {
        $this->dateCaptured = $dateCaptured;
    }

    /**
     * @return DateTime
     */
    public function getDateSettled()
    {
        return $this->dateSettled;
    }

    /**
     * @param DateTime $dateSettled
     */
    public function setDateSettled($dateSettled)
    {
        $this->dateSettled = $dateSettled;
    }

    /**
     * @return DateTime
     */
    public function getDateUnpaid()
    {
        return $this->dateUnpaid;
    }

    /**
     * @param DateTime $dateUnpaid
     */
    public function setDateUnpaid($dateUnpaid)
    {
        $this->dateUnpaid = $dateUnpaid;
    }

    /**
     * @return DeezerPayment
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param DeezerPayment $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }




}

