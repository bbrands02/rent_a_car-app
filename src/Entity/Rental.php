<?php

namespace App\Entity;

use App\Repository\RentalRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RentalRepository::class)
 */
class Rental
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $endDate;

    /**
     * @Assert\Choice(
     *     {
     *     "pending",
     *     "accepted",
     *     "ready",
     *     "rented",
     *     "returned",
     *     "finished"
     *     }
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rentedBy;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModified;

    public function getId()
    {
        return $this->id;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?String
    {
        return $this->status;
    }

    public function setStatus(String $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRentedBy(): ?Person
    {
        return $this->rentedBy;
    }

    public function setRentedBy(Person $rentedBy): self
    {
        $this->rentedBy = $rentedBy;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateModified(?\DateTime $dateModified)
    {
        $this->dateModified = $dateModified;

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
}
