<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderCustomerRepository")
 */
class OrderCustomer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre prénom !")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre nom !")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today")
     */
    private $dateOfVisit;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOfOrder;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfTickets;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $orderStatus;

    /**
     * @ORM\Column(type="boolean")
     */
    private $halfDay;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="orderCustomer", orphanRemoval=true)
     */
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateOfVisit(): ?\DateTimeInterface
    {
        return $this->dateOfVisit;
    }

    public function setDateOfVisit(\DateTimeInterface $dateOfVisit): self
    {
        $this->dateOfVisit = $dateOfVisit;

        return $this;
    }

    public function getDateOfOrder(): ?\DateTimeInterface
    {
        return $this->dateOfOrder;
    }

    public function setDateOfOrder(\DateTimeInterface $dateOfOrder): self
    {
        $this->dateOfOrder = $dateOfOrder;

        return $this;
    }

    public function getNumberOfTickets(): ?int
    {
        return $this->numberOfTickets;
    }

    public function setNumberOfTickets(int $numberOfTickets): self
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getHalfDay(): ?bool
    {
        return $this->halfDay;
    }

    public function setHalfDay(bool $halfDay): self
    {
        $this->halfDay = $halfDay;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setOrderCustomer($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getOrderCustomer() === $this) {
                $customer->setOrderCustomer(null);
            }
        }

        return $this;
    }
}
