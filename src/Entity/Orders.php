<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $client_name;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $client_phone;

    /**
     * @ORM\Column(type="string", length=400)
     */
    private $_order;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $paid;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $shipping_details;

    /**
     * @ORM\Column(type="integer")
     */
    private $shipping;

    /**
     * @ORM\Column(type="datetime")
     */
    private $order_created_at;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ttn;

    /**
     * @ORM\ManyToOne(targetEntity=OrserStatuses::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statusId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getClientPhone(): ?string
    {
        return $this->client_phone;
    }

    public function setClientPhone(?string $client_phone): self
    {
        $this->client_phone = $client_phone;

        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->_order;
    }

    public function setOrder(string $_order): self
    {
        $this->_order = $_order;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPaid(): ?int
    {
        return $this->paid;
    }

    public function setPaid(?int $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getShippingDetails(): ?string
    {
        return $this->shipping_details;
    }

    public function setShippingDetails(string $shipping_details): self
    {
        $this->shipping_details = $shipping_details;

        return $this;
    }

    public function getShipping(): ?int
    {
        return $this->shipping;
    }

    public function setShipping(int $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getOrderCreatedAt(): ?\DateTimeInterface
    {
        return $this->order_created_at;
    }

    public function setOrderCreatedAt(\DateTimeInterface $order_created_at): self
    {
        $this->order_created_at = $order_created_at;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getTtn(): ?string
    {
        return $this->ttn;
    }

    public function setTtn(string $ttn): self
    {
        $this->ttn = $ttn;

        return $this;
    }

    public function getStatusId(): ?OrserStatuses
    {
        return $this->statusId;
    }

    public function setStatusId(?OrserStatuses $statusId): self
    {
        $this->statusId = $statusId;

        return $this;
    }

    




}
