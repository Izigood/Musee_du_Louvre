<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\LessThan("today")
     */
    private $dateOfBirthday;

    /**
     * @ORM\Column(type="boolean")
     * 
     */
    private $reducedPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $ticketPrice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderCustomer", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderCustomer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDateOfBirthday(): ?\DateTimeInterface
    {
        return $this->dateOfBirthday;
    }

    public function setDateOfBirthday(\DateTimeInterface $dateOfBirthday): self
    {
        $this->dateOfBirthday = $dateOfBirthday;

        return $this;
    }

    public function getReducedPrice(): ?bool
    {
        return $this->reducedPrice;
    }

    public function setReducedPrice(bool $reducedPrice): self
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    public function getTicketPrice(): ?float
    {
        return $this->ticketPrice;
    }

    public function setTicketPrice(float $ticketPrice): self
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    public function getOrderCustomer(): ?OrderCustomer
    {
        return $this->orderCustomer;
    }

    public function setOrderCustomer(?OrderCustomer $orderCustomer): self
    {
        $this->orderCustomer = $orderCustomer;

        return $this;
    }
}
